<?php

namespace App\Traits;

use App\Designs\PdfColumns;
use App\Lead;
use App\PdfData;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\View\Factory;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable;

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

        $data = [];
        $data['entity'] = $entity;
        $data['lang'] = $entity->customer->preferredLocale();

        $css_link = '<link href="' . public_path() . '/css/pdf.css" rel="stylesheet">';
        $data['includes'] = str_replace('$css_link', $css_link, $designer->getSection('includes'));
        $data['includes'] = $data['includes'];
        $data['header'] = $designer->getSection('header');
        $table = $designer->getSection('table');

        $data['body'] = str_replace('$table_here', $table, $designer->getSection('body'));
        $data['footer'] = $designer->getSection('footer');

        $html = view('pdf.stub', $data)->render();

        //$html = str_replace('$total_tax_labels', $labels['$total_tax_values_label'], $html);

        $html = $this->generateCustomCSS($entity, $html);

        $html = $objPdf->parseLabels($labels, $html);
        $html = $objPdf->parseValues($values, $html);

        // echo $html;
        // die;

        return $html;
    }

    private function generateCustomCSS($entity, $html)
    {
        $settings = $entity->account->settings;

        if ($settings->all_pages_header && $settings->all_pages_footer) {
            $html = str_replace('header_class', 'header', $html);
            $html = str_replace('footer_class', 'footer', $html);
        } elseif ($settings->all_pages_header && !$settings->all_pages_footer) {
            $html = str_replace('header_class', 'header', $html);
        } elseif (!$settings->all_pages_header && $settings->all_pages_footer) {
            $html = str_replace('footer_class', 'footer', $html);
        }
        $css = '
html {
        ';

        $css .= 'font-size:' . $settings->font_size . 'px;';

        $css .= '}';

       $html = str_replace('$custom_css', $css, $html);

       return $html;

    }
}
