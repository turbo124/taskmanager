<?php

namespace App\Designs;

class Photo extends AbstractDesign
{

    public function __construct()
    {
    }


    public function includes()
    {
        return '<title>$number</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
$css_link
<style>
#imageContainer {
    background-image: url();
    background-size: cover;
}
.table_header_thead_class { text-align: left; border-bottom-width: 4px; border-color: black; }
.table_header_td_class { font-weight: 400; text-transform: uppercase; padding: 1rem .5rem; }
.table_body_td_class { padding: 1rem; }
$custom_css
</style>';
    }


    public function header()
    {

        return '<div class="px-4 py-4">
<div class="mt-4">
    <div class="inline-block" ref="logo" style="width: 50%">
        $company_logo
    </div>
    <div class="inline-block" style="width: 40%">
        <div class="inline-block-col mr-4">
            $entity_labels
        </div>
        <div class="inline-block text-right">
            $entity_details
        </div>
    </div>
</div>
</div>';

    }

    public function body()
    {

        return '<div class="bg-secondary h-auto p-4 pt-4" id="imageContainer">
<div>
    <div class="inline-block mr-4" style="width: 40%">
        <p class="text-uppercase text-warning">$to_label:</p>
        <div class="ml-4 mt-4 text-white">
            $client_details
        </div>
    </div>
    <div class="inline-block" style="width: 30%">
        <p class="text-uppercase text-warning">$from_label:</p>
        <div class="ml-4 text-white">
            $company_details
        </div>
    </div>
</div>
</div>

$table_here

<div class="mt-2 px-4 pb-4">
<div class="inline-block mt-4" style="width: 50%">
    <div>
        <p>$entity.public_notes</p>
    </div>
</div>
<div class="inline-block" style="width: 40%">
    <div class="px-3 mt-2">
        <section class="inline-block col-6 text-right">
            $discount_label
            $total_tax_labels
            $line_tax_labels
        </section>
        <section class="inline-block col-6 text-right">
            $discount
            $total_tax_values
            $line_tax_values
        </section>
    </div>
</div>
</div>
<div class="mt-4 pb-4 px-4">
<div class="inline-block" style="width: 50%">
    <div>
        <p class="font-weight-bolder">$terms_label</p>
        <p>$terms</p>
    </div>
</div>
<div class="inline-block" style="width: 40%">
    <section class="bg-warning py-2 text-white px-2 mt-1">
        <p class="w-1/2">$balance_due_label</p>
        <p class="text-right w-1/2">$balance_due</p>
    </section>
</div>
</div>
</div>
</div>';

    }

    public function table()
    {
        return '<table class="w-100 table-auto">
<thead class="text-left border-bottom-4 border-dark">
    $product_table_header
</thead>
<tbody>
    $product_table_body
</tbody>
</table>';
    }

    public function getTaskTable()
    {
        return '
<table class="w-100 table-auto">
<thead class="text-left border-bottom-4 border-dark">
    $task_table_header
</thead>
<tbody>
    $task_table_body
</tbody>
</table>';
    }

    public function task()
    {
        return '';
    }

    public function product()
    {
        return '';
    }

    public function footer()
    {

        return '<div class="footer py-4 px-4" style="page-break-inside: avoid;"></div>
</footer>
</body>
</html>';

    }

}
