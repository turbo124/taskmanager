<?php

namespace App\Traits;

use App\Designs\Designer;
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
     * @param Designer $designer
     * @param $entity
     * @param null $contact
     * @return string
     */
    public function generateEntityHtml(PdfData $objPdf, Designer $designer, $entity, $contact = null): string
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
        $data['includes'] = str_replace('$custom_css', $this->generateCustomCSS($entity), $data['includes']);
        $data['header'] = $designer->getSection('header');
        $table = $designer->getSection('table');

        $data['body'] = str_replace('$table_here', $table, $designer->getSection('body'));
        $data['footer'] = $designer->getSection('footer');

        $html = view('pdf.stub', $data)->render();

        $html = str_replace('$total_tax_labels', $labels['$total_tax_values_label'], $html);

        $html = $objPdf->parseLabels($labels, $html);
        $html = $objPdf->parseValues($values, $html);

        //  echo $html;
        // die;

        return $html;
    }

    private function generateCustomCSS($entity)
    {
        $settings = $entity->account->settings;

        $footer = '
           .footer {
             position: fixed; 
             bottom: 0px; 
             left: 0px; 
             right: 0px; 
             background-color: #000; 
             height: 50px;
             width: 100%;
           }';

        $header = '
              .header {
                 position: fixed; 
                 top: 0px;
                 left: 0px; 
                 right: 0px; 
                 background-color: lightblue; 
                 width: 100%;
               }
             ';

        $css = '';

        if ($settings->all_pages_header && $settings->all_pages_footer) {
            $css .= $header;
            $css .= $footer;
        } elseif ($settings->all_pages_header && !$settings->all_pages_footer) {
            $css .= $header;
        } elseif (!$settings->all_pages_header && $settings->all_pages_footer) {
            $css .= $footer;
        }
        $css .= '
            .header-space {
  height: 160px;
}
.footer-space {
  height: 160px;
}
.page {
  page-break-after: always;
}
@page {
  margin: 0mm
}
html {
        ';

        $css .= 'font-size:' . $settings->font_size . 'px;';
//        $css .= 'font-size:14px;';

        $css .= '}';

        return $css;

    }
}
