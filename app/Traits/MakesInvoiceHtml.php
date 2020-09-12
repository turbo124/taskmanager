<?php

namespace App\Traits;

use App\Designs\PdfColumns;
use Illuminate\Support\Facades\App;
use ReflectionClass;
use ReflectionException;

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
    public function generateEntityHtml($objPdf, PdfColumns $designer, $entity, $contact = null): string
    {
        switch (get_class($entity)) {
            case 'App\Models\Lead':
                $lang = $entity->preferredLocale();
                App::setLocale($lang);
                break;
            case 'App\Models\PurchaseOrder':
                $lang = $entity->company->preferredLocale();
                App::setLocale($lang);
                break;
            default:
                $lang = $entity->customer->preferredLocale();
                App::setLocale($lang);
                break;
        }

        $objPdf->build($contact);
        $labels = $objPdf->getLabels();
        $values = $objPdf->getValues();

        $designer->buildDesign();

        $table = in_array(
            get_class($entity),
            ['App\Models\Task', 'App\Models\Cases', 'App\Models\Deal']
        ) ? $designer->getSection('task_table') : $designer->getSection('table');

        $settings = $entity->account->settings;

        $client_signature = $this->getClientSignature($entity, $contact);

        if (in_array(get_class($entity), ['App\Models\Lead', 'App\Models\PurchaseOrder'])) {
            $signature = !empty($settings->email_signature) && $entity->account->settings->show_signature_on_pdf === true ? '<span style="margin-bottom: 20px; margin-top:20px">Your Signature</span> <br><br><br><img style="display:block; width:100px;height:100px;" id="base64image" src="' . $settings->email_signature . '"/>' : '';

            $client_signature = !empty($client_signature) && $entity->account->settings->show_signature_on_pdf === true ? '<span style="margin-bottom: 20px">Client Signature</span> <br><br><br><img style="display:block; width:100px;height:100px;" id="base64image" src="' . $client_signature . '"/>' : '';
        } else {
            $signature = !empty($settings->email_signature) && $entity->customer->getSetting(
                'show_signature_on_pdf'
            ) === true ? '<span style="margin-bottom: 20px; margin-top:20px">Your Signature</span> <br><br><br><img style="display:block; width:100px;height:100px;" id="base64image" src="' . $settings->email_signature . '"/>' : '';

            $client_signature = !empty($client_signature) && $entity->customer->getSetting(
                'show_signature_on_pdf'
            ) === true ? '<span style="margin-bottom: 20px">Client Signature</span> <br><br><br><img style="display:block; width:100px;height:100px;" id="base64image" src="' . $client_signature . '"/>' : '';
        }

        $footer = $designer->getSection('footer');
        $footer = str_replace('$signature_here', $signature, $footer);
        $footer = str_replace('$client_signature_here', $client_signature, $footer);

        $data = [
            'entity'   => $entity,
            'lang'     => $lang,
            'settings' => $settings,
            'header'   => $designer->getSection('header'),
            'body'     => str_replace('$table_here', $table, $designer->getSection('body')),
            'footer'   => $footer
        ];

        $html = view('pdf.stub', $data)->render();
        $html = $this->generateCustomCSS($settings, $html);

        if (in_array(
            get_class($entity),
            ['App\Models\Task', 'App\Models\Cases', 'App\Models\Deal', 'App\Models\Lead']
        )) {
            $html = str_replace('$costs', '', $html);
        } else {
            $html = str_replace('$costs', $designer->getSection('totals'), $html);
        }

        $entity_class = (new ReflectionClass($entity))->getShortName();


        $html = $objPdf->parseLabels($labels, $html);
        $html = $objPdf->parseValues($values, $html);
        $html = str_replace('$pdf_type', ucfirst($entity_class), $html);
        $html = str_replace('$entity_number', $entity->number, $html);


        if (empty($entity->voucher_code)) {
            $html = str_replace(['$voucher_label', '$voucher'], '', $html);
        }

//        echo $html;
//        die;

        return $html;
    }

    /**
     * @param $entity
     * @param $contact
     * @return string|null
     * @throws ReflectionException
     */
    private function getClientSignature($entity, $contact = null): ?string
    {
        if (!in_array(get_class($entity), ['App\Models\Invoice', 'App\Models\Quote'])) {
            return null;
        }

        $invitation_class = 'App\Models\\' . (new ReflectionClass($entity))->getShortName() . 'Invitation';

        $invitations = $invitation_class::all();

        $selected_invitation = null;

        if (!empty($contact)) {
            $selected_invitation = $invitation_class::where('contact_id', '=', $contact->id);
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
            $html = str_replace('header-space', 'header-margin', $html);
        } elseif ($settings->all_pages_header && !$settings->all_pages_footer) {
            $html = str_replace('header_class', 'header', $html);
            $html = str_replace('header-space', 'header-margin', $html);
        } elseif (!$settings->all_pages_header && $settings->all_pages_footer) {
            $html = str_replace('footer_class', 'footer', $html);
        }

        return $html;
    }
}
