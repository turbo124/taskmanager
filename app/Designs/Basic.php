<?php

namespace App\Designs;

class Basic
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

        return ' <div class="px-2 py-4">
<div>
    $company_logo
    <div class="inline-block" style="word-break: break-word">
        $company_details
    </div>
</div>
    <div class="inline-block mr-4 mt-4" style="width: 60%;">
        <div class="inline-block">
            <section class="">
                $entity_details
            </section>
        </div>
        <section class="px-3">
            <p class="col-6 mr-4">$balance_label</p>
            <p>$balance_due</p>
        </section>
</div>';

    }

    public function body()
    {

        return '<div class="inline-block">
    $client_details
</div>

$table_here

<div class="mt-4">
<div class="inline-block col-6">
    <div class="">
        <p>$entity.public_notes</p>
        <div class="pt-4">
            <p class="font-bold">$terms_label</p>
            <p>$terms</p>
        </div>
    </div>
</div>
<div class="inline-block">
    <div class="inline-block px-3 mt-4">
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
    <section class="inline-block px-3 mt-1">
        <p class="col-6 text-right">$balance_due_label</p>
        <p class="text-right col-6">$balance_due</p>
    </section>
</div>
</div>
';

    }

    public function table()
    {
        return '<table class="w-100 table-auto mt-4">
    <thead class="text-left bg-gray-300">
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
<table class="w-100 table-auto mt-4">
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
