<?php

namespace App\Designs;

/**
 * @wip: Table margins act weird.
 */
class Creative extends AbstractDesign
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
.table_header_thead_class { text-align: left; border-radius: .5rem; }
.table_header_td_class { text-transform: uppercase; font-size: 1.25rem; color: #b83280; padding: 1.25rem 1rem; font-weight: 500 }
.table_body_td_class { padding: 1rem;}
$custom_css
</style>';
    }


    public function header()
    {

        return '<div class="py-4 px-4 mt-4">
            <div style="width: 100%">
                <div class="inline-block mt-4" style="width: 30%">
                    <div class="inline-block">
                        $client_details
                    </div>
                    <div class="inline-block ml-4">
                        $company_details
                    </div>
                    <div class="inline-block ml-4 mr-4">
                        $company_address
                    </div>
                </div>
                
                <div style="width: 60%">
    $company_logo
    </div>
</div>';

    }

    public function body()
    {

        return '<div class="mt-4">
<div class="inline-block" style="width: 60%">
    <h1 class="text-uppercase font-weight-bolder">$entity_label</h1>
    <i class="ml-4 text-danger">$entity_number</i>
</div>
<div class="inline-block" style="width: 30%">
    <div class="inline-block">
        $entity_labels
    </div>
    <div class="inline-block text-right">
        $entity_details
    </div>
</div>
</div>

$table_here

<div class="border-4 border-danger">
<div class="mt-2 px-4 pb-4">
    <div class="inline-block" style="width: 50%">
        <div class="">
            <p>$entity.public_notes</p>
        </div>
    </div>
    <div class="inline-block" style="width: 40%">
        <div class="px-3 mt-2">
            <div class="inline-block col-6 text-right">
                <span>$subtotal_label</span>
                <span>$discount_label</span>
                <span>$paid_to_date_label</span>
            </div>
            <div class="inline-block col-6 text-right">
                <span>$subtotal</span>
                <span>$discount</span>
                <span>$paid_to_date</span>
            </div>
        </div>
    </div>
</div>
<div class="flex items-center justify-between mt-4 pb-4 px-4">
    <div style="width: 50%">
        <div>
            <p class="font-weight-bolder">$terms_label</p>
            <p>$terms</p>
        </div>
    </div>
</div>
</div>
<div class="mt-4" style="width: 40%">
    <p>$balance_due_label</p>
    <p class="ml-4 text-danger font-weight-bolder">$balance_due</p>
    </div>
</div>

';
    }

    public function table()
    {
        return '<table class="w-100 table-auto mt-4 border-top-4 border-danger bg-white">
<thead class="text-left rounded">
    $product_table_header
</thead>
<tbody>
    $product_table_body
</tbody>
</table>';
    }

    public function getTaskTable()
    {
        return '<table class="w-100 table-auto mt-4 border-t-4 border-pink-700 bg-white">
    <thead class="text-left rounded">
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
