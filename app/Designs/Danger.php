<?php

namespace App\Designs;

/**
 * @wip: Table margins act weird.
 */
class Danger
{

    public function header()
    {

        return '<div class="py-4 px-4 mt-2">
            <div style="width: 100%">
                <div class="inline-block mt-4" style="width: 30%">
                    <div class="inline-block">
                        $customer_details
                    </div>
                    <div class="inline-block ml-4">
                        $account_details
                    </div>
                    <div class="inline-block ml-4 mr-4">
                        $account_address
                    </div>
                </div>
                
                <div class="mt-4" style="width: 60%">
    $account_logo
    </div>
</div>';

    }

    public function body()
    {

        return '<div class="mt-4">
<div class="inline-block" style="width: 60%">
    <h1 class="text-uppercase font-weight-bold">$entity_label</h1>
    <i class="ml-4 text-danger">$entity_number</i>
</div>
<div class="inline-block text-left" style="width: 30%">
    <div class="inline-block">
        $entity_labels
    </div>
    <div class="inline-block text-right">
        $entity_details
    </div>
</div>
</div>

$table_here

<div class="border-top-4 border-danger">
<div class="mt-4 px-4 pb-4">
    <div class="inline-block" style="width: 70%">
        <div class="">
            <p>$entity.public_notes</p>
        </div>
    </div>
    <div class="inline-block" style="width: 20%">
        <div class="px-3 mt-2">
            <div class="inline-block col-6 text-left">
                <span style="margin-right: 80px">$subtotal_label</span> $subtotal <br>
                <span style="margin-right: 80px">$discount_label</span> $discount <br>
                <span style="margin-right: 80px">$tax_label</span> $tax <br>
                <span style="margin-right: 80px">$balance_due_label</span> <span class="text-danger font-weight-bold">$balance_due</span> <br>
            </div>
        </div>
    </div>
</div>
<div class="mt-1 pb-4 px-4">
    <div style="width: 70%">
        <div>
            <p class="font-weight-bold">$terms_label</p>
            <p>$terms</p>
        </div>
    </div>
</div>
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

    public function task_table()
    {
        return '<table class="w-100 table-auto mt-4 border-top-4 border-danger bg-white">
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

        return '
        <div class="text-center mb-2">
               $signature_here
           </div>
        
        <div class="footer_class py-4 px-4" style="page-break-inside: avoid;">
        </div>
</footer>
</body>
</html>';

    }

}
