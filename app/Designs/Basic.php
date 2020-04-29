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
        <div class="">
            <section class="">
                $entity_details
            </section>
        </div>
</div>';

    }

    public function body()
    {

        return '<div class="inline-block">
    $client_details
</div>

$table_here

<div class="mt-4">
<div class="inline-block col-6" style="width: 70%">
    <div class="">
        <p>$entity.public_notes</p>
        <div class="pt-4">
            <p class="font-bold">$terms_label</p>
            <p>$terms</p>
        </div>
    </div>
</div>
<div class="inline-block" style="width: 20%;">
    <div class="inline-block px-3">
        <div class="col-6 text-left">
            <span style="margin-right: 20px"> $discount_label </span>  $discount<br>
            <span style="margin-right: 20px">$tax_label</span> $tax<br>
            <span style="margin-right: 20px"> $balance_due_label </span>  $balance_due<br>
        </div>
    </div>
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
