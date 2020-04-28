<?php

namespace App\Designs;

class Info
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

        return '<div class="bg-secondary p-4">
<div class="col-6 inline-block mt-4">
    <div class="bg-white pt-4 px-4 pb-4 inline-block">
        $company_logo
    </div>
</div>
<div class="col-6 inline-block">
    <div class="text-white">
        $company_details
    </div>
    <div class="inline-block text-white">
        $company_address
    </div>
</div>
</div>';

    }

    public function body()
    {
        return '<div class="mt-4 pl-4">
    <div class="inline-block col-6 mr-4" style="width: 40%">
        <h2 class="text-uppercase font-weight-bolder text-info">$entity_label</h2> $client_details
    </div>
    <div class="inline-block col-6">
        <div class="bg-info px-4 py-3 text-white">
            <div class="text-white">
                $entity_details
            </div>
        </div>
    </div>
</div>

$table_here

   <div class="px-4 mt-4" style="width: 100%">
    <div class="inline-block" style="width: 60%">
        $entity.public_notes
    </div>
    <div class="inline-block" style="width: 30%">
        <div class="col-6 text-right">
            $subtotal_label $discount_label $total_tax_labels $line_tax_labels 
        </div>
        <div class="inline-block col-6 text-right">
            $subtotal $discount $total_tax_values $line_tax_values 
        </div>
    </div>
</div>
<div class="px-4 mt-4" style="width: 100%">
    <div class="inline-block" style="width: 60%">
        <p class="font-weight-bolder">$terms_label</p>
        $terms
    </div>
    <div class="inline-block" style="width: 30%">
        <div class="col-6 text-right">
            <span class="font-weight-bolder">$balance_due_label</span>
        </div>
        <div class="inline-block col-6 text-right">
            <span class="text-info font-weight-bolder">$balance_due</span>
        </div>
    </div>
</div>';
    }

    public function table()
    {
        return '<table class="w-100 table-auto mt-4">
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

        return '<div class="footer_class py-4 px-4" style="page-break-inside: avoid;"></div>';

    }

}
