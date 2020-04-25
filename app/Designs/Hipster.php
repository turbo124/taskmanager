<?php

namespace App\Designs;

class Hipster
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

        return '<div class="px-4 py-4">
<div class="mt-4 mb-4" style="width: 100%">
    <div class="inline-block border-left pl-2 border-dark mr-4 mt-4" style="width: 30%">
        <p class="font-weight-bolder text-uppercase text-info">From:</p>
        <div>
            <div class="mr-5">
                $company_details
            </div>
            <div>
                $company_address
            </div>
        </div>
    </div>
    <div class="border-left pl-4 border-dark inline-block" style="width: 30%">
        <p class="font-semibold text-uppercase text-info">To:</p>
        $client_details
    </div>
    <div class="inline-block mt-4 h-16" style="width: 30%">
        $company_logo
    </div>
</div>';

    }

    public function body()
    {

        return '<div class="flex flex-col mx-4 mt-4">
<h1 class="font-weight-bolder text-uppercase">$entity_label</h1>
<div class="mt-1">
    <span class="font-weight-bolder text-uppercase text-info">$entity_number</span>
    <div class="inline-block ml-4">
        <span class="text-uppercase">$date_label</span>
        <span>$date</span>
    </div>
    <div class="inline-block ml-4">
        <span class="text-uppercase">$due_date_label</span>
        <span>$due_date</span>
    </div>
    <div class="inline-block ml-4">
        <span class="text-uppercase">$balance_label</span>
        <span class="text-info">$balance_due</span>
    </div>
</div>
</div>

$table_here

<div class="mt-4">
<div class="inline-block" style="width: 50%">
    <div>
        <p>$entity.public_notes</p>
        <div class="pt-4">
            <p class="font-weight-bold">$terms_label</p>
            <p>$terms</p>
        </div>
    </div>
</div>
<div class="inline-block" style="width: 40%">
    <div class="inline-block px-3 mt-4">
        <section class="col-6 text-right">
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
    <section class="inline-block bg-dark text-white px-3 mt-1">
        <p class="col-6 text-right">$balance_due_label</p>
        <p class="text-right col-6">$balance_due</p>
    </section>
</div>
</div>
</div>
';

    }

    public function table()
    {
        return '<table class="mt-4 w-100 table-auto">
<thead class="text-left">
    $product_table_header
</thead>
<tbody>
    $product_table_body
</tbody>
</table>';
    }

    public function getTaskTable() {
        return '<table class="w-100 table-auto mt-4">
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
