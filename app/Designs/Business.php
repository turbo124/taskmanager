<?php

namespace App\Designs;

class Business
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
$custom_css
</style>';
    }

    public function header()
    {

        return '<div class="my-4 mx-4">
<div>
    <div class="inline-block" style="width: 10%">
        $company_logo
    </div>
    <div class="ml-4 inline-block" style="width: 80%">
        <div class="text-secondary">
            $company_details
        </div>
        <div class="text-secondary ml-4">
            $company_address
        </div>
    </div>
</div>';

    }

    public function body()
    {

        return '<div class="mt-4">
    <div class="inline-block" style="width: 40%">
        <span>$entity_label</span>
        <section class="text-warning mt-4">
            $client_details
        </section>
    </div>
    <div class="inline-block col-6 ml-4 bg-warning px-4 py-4 rounded" style="width: 40%;">
        <div class="flex text-white">
            <section class="col-6">
                $entity_labels
            </section>
            <section class="">
                $entity_details
            </section>
        </div>
    </div>
</div>

$table_here

<div class="mt-4 px-4 pt-4 pb-4 bg-secondary rounded py-2" style="width: 100%">
    <div class="inline-block" style="width: 60%">
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
<div class="mt-4 pb-4 px-4" style="width: 100%">
<div class="inline-block" style="width: 60%">
    <div class="">
        <p class="font-weight-bolder">$terms_label</p>
        <p>$terms</p>
    </div>
</div>
<div class="inline-block" style="width: 40%">
    <section class="py-2 bg-primary px-4 py-3 rounded text-white">
        <p class="col-6">$balance_due_label</p>
        <p class="text-right col-6">$balance_due</p>
    </section>
</div>
</div>
</div>';

    }

    public function table()
    {
        return '
        <table class="w-100 table-auto mt-4">
    <thead class="text-left">
        $product_table_header
    </thead>
    <tbody>
        $product_table_body
    </tbody>
</table>';
    }

    public function getTaskTable() {
        return '
<table class="table-auto mt-4">
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

        return '<div class="footer_class py-4 px-4" style="page-break-inside: avoid;"></div>
</body>
</html>';

    }

}
