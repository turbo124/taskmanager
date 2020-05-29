<?php

namespace App\Traits;

use App\Designs\PdfColumns;
use App\InvoiceInvitation;
use App\Lead;
use App\PdfData;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\View\Factory;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable;
use Illuminate\Support\Facades\Storage;

/**
 * Class MakesInvoiceHtml.
 */
trait MakesInvoiceHtml
{
    /**
     * @param PdfColumns $designer
     * @param $entity
     * @param null $contact
     * @return string
     */
    public function generateEntityHtml(PdfData $objPdf, PdfColumns $designer, $entity, $contact = null): string
    {
        App::setLocale($entity->customer->preferredLocale());

        $objPdf->build($contact);
        $labels = $objPdf->getLabels();
        $values = $objPdf->getValues();

        $designer->buildDesign();

        $table = $designer->getSection('table');
        $settings = $entity->account->settings;
        $signature = !empty($settings->email_signature) && $entity->customer->getSetting(
            'show_signature_on_pdf'
        ) === true ? '<span style="margin-bottom: 20px">Your Signature</span> <br><br><br><img style="display:block; width:100px;height:100px;" id="base64image" src="' . $settings->email_signature . '"/>' : '';

        $client_signature = $this->getClientSignature($entity, $contact);

        $client_signature = !empty($client_signature) && $entity->customer->getSetting(
            'show_signature_on_pdf'
        ) === true ? '<span style="margin-bottom: 20px">Client Signature</span> <br><br><br><img style="display:block; width:100px;height:100px;" id="base64image" src="' . $client_signature . '"/>' : '';

        $footer = $designer->getSection('footer');
        $footer = str_replace('$signature_here', $signature, $footer);
        $footer = str_replace('$client_signature_here',  $client_signature, $footer);

        $data = [
            'entity'   => $entity,
            'lang'     => $entity->customer->preferredLocale(),
            'settings' => $settings,
            'header'   => $designer->getSection('header'),
            'body'     => str_replace('$table_here', $table, $designer->getSection('body')),
            'footer'   => $footer
        ];

        $html = view('pdf.stub', $data)->render();
        $html = $this->generateCustomCSS($settings, $html);

        $html = $objPdf->parseLabels($labels, $html);
        $html = $objPdf->parseValues($values, $html);

        return $html;
    }

    /**
     * @param $entity
     * @param $contact
     * @return string|null
     * @throws \ReflectionException
     */
    private function getClientSignature($entity, $contact = null): ?string
    {
        if (!in_array(get_class($entity), ['App\Invoice', 'App\Quote'])) {
            return null;
        }

        $invitation_class = 'App\\' . (new \ReflectionClass($entity))->getShortName() . 'Invitation';

        $invitations = $invitation_class::all();

        $selected_invitation = null;

        if (!empty($contact)) {
            $selected_invitation = $invitation_class::where('client_contact_id', '=', $contact->id);
        } else {
            foreach ($invitations as $invitation) {
                if (!empty($invitation->client_signature)) {
                    $selected_invitation = $invitation;
                    break;
                }
            }
        }

        if (!empty($selected_invitation->client_signature)) {
            return $selected_invitation->client_signature;
        }

        return null;
    }

    private
    function generateCustomCSS(
        $settings,
        $html
    ) {
        if ($settings->all_pages_header && $settings->all_pages_footer) {
            $html = str_replace('header_class', 'header', $html);
            $html = str_replace('footer_class', 'footer', $html);
        } elseif ($settings->all_pages_header && !$settings->all_pages_footer) {
            $html = str_replace('header_class', 'header', $html);
        } elseif (!$settings->all_pages_header && $settings->all_pages_footer) {
            $html = str_replace('footer_class', 'footer', $html);
        }

        return $html;
    }
}
