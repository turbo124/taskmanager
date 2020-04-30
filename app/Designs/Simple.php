<?php

namespace App\Designs;

class Simple
{
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

        return '<div class="px-3 my-4">
<div class="">
    <div class="inline-block mt-2">
        <div class="">$account_logo</div>
    </div>
    <div class="inline-block">
        <div class="mr-4 text-secondary">
            $account_details
        </div>
        <div class="ml-5 text-secondary">
            $account_address
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
    <div class="">
        <div>
            $entity_labels
        </div>
        <div style="width: 60%" class="inline-block">
            $entity_details
        </div>
        <div class="inline-block mt-4">
            $customer_details
        </div>
    </div>
</div>
<div class="border-bottom border-secondary"></div>

$table_here

<div class="px-4 mt-4" style="width: 100%">
    <div class="inline-block" style="width: 80%">
        $entity.public_notes
    </div>
    <div class="inline-block" style="width: 20% !important;">
        <div class="inline-block col-6 text-left">
            <span style="margin-right: 20px"> $discount_label </span> $discount <br>
            <span style="margin-right: 20px"> $tax_label </span> $tax
        </div>
    </div>
</div>
    <div class="px-4 mt-4" style="width: 100%">
        <div class="inline-block" style="width: 80%">
            <p class="font-weight-bolder">$terms_label</p>
            $terms
        </div>
        <div class="inline-block" style="width: 20%">
            <div class="text-left col-6">
                <span style="margin-right: 20px">$balance_due_label</span> <span class="text-primary">$balance_due</span>
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
</footer>
</body>
</html>';

    }

}
