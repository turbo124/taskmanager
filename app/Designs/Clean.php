<?php

namespace App\Designs;

class Clean extends AbstractDesign
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
    .table_header_thead_class { text-align: left; }
    .table_header_td_class { padding: .5rem 1rem;}
    .table_body_td_class { border-bottom-width: 1px; border-top-width: 1px; border-color: #cbd5e0; padding: 1rem;}
     $custom_css
</style>';
    }


    public function header()
    {

        return '<div class="px-3 my-4">
<div class="">
    <div class="inline-block mt-2">
        <div class="">$company_logo</div>
    </div>
    <div class="inline-block">
        <div class="mr-4 text-secondary">
            $company_details
        </div>
        <div class="ml-5 text-secondary">
            $company_address
        </div>
    </div>
</div>';
    }

    public function body()
    {

        return '<h1 class="mt-4 text-uppercase text-primary ml-4">
    $entity_label
</h1>
<div class="border-bottom border-secondary"></div>
<div class="ml-4 py-4">
    <div class="flex">
        <div class="w-40">
            $entity_labels
        </div>
        <div style="width: 60%" class="inline-block">
            $entity_details
        </div>
        <div class="inline-block mt-4">
            $client_details
        </div>
    </div>
</div>
<div class="border-bottom border-secondary"></div>

$table_here

<div class="px-4 mt-4" style="width: 100%">
    <div class="inline-block" style="width: 50%">
        $entity.public_notes
    </div>
    <div class="inline-block" style="width: 40% !important;">
        <div class="inline-block col-6 text-right">
            $discount_label
            $total_tax_label
            $line_tax_label
        </div>
        <div class="inline-block col-6 text-right">
            $discount
            $total_tax_values
            $line_tax_values
        </div>
    </div>
</div>
    <div class="px-4 mt-4" style="width: 100%">
        <div class="inline-block" style="width: 50%">
            <p class="font-weight-bolder">$terms_label</p>
            $terms
        </div>
        <div class="inline-block" style="width: 40%">
            <div class="text-right col-6">
                <span>$balance_due_label</span>
            </div>
            <div class="text-right col-6">
                <span class="text-primary">$balance_due</span>
            </div>
        </div>
    </div>
</div>';

    }

    public function table()
    {
        return '
        <table class="col-6 table-auto mt-4">
    <thead class="text-left">
        $product_table_header
    </thead>
    <tbody>
        $product_table_body
    </tbody>
</table>';
    }

    public function getTaskTable()
    {
        return '<table class="table-auto mt-4">
    <thead class="text-left">
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
