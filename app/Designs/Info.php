<?php

namespace App\Designs;

class Info
{
    public function header()
    {
        return '<div class="bg-secondary p-4">
<div class="col-6 inline-block mt-4">
    <div class="bg-white pt-4 px-4 pb-4 inline-block">
        $account_logo
    </div>
</div>
<div class="col-6 inline-block">
    <div class="text-white">
        $account_details
    </div>
    <div class="inline-block text-white">
        $account_address
    </div>
</div>
</div>';
    }

    public function body()
    {
        return '<div class="mt-4 pl-4">
    <div class="inline-block col-6 mr-4" style="width: 40%">
        <h2 class="text-uppercase font-weight-bolder text-info">$entity_label</h2> $customer_details
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

   <div class="px-4" style="width: 100%; margin-top: 80px; margin-bottom: 80px">
    <div class="inline-block" style="width: 70%">
        $entity.public_notes
    </div>
    <div class="inline-block" style="width: 20%">
        <div class="col-6 text-left">
            <span style="margin-right: 20px">$subtotal_label</span> $subtotal<br>
            <span style="margin-right: 20px">$discount_label</span> $discount<br>
            <span style="margin-right: 20px">$tax_label</span> $tax<br>
             <span style="margin-right: 20px">$shipping_cost_label</span> $shipping_cost<br>
             <span style="margin-right: 20px">$voucher_label</span> $voucher<br>
            <span class="font-weight-bold" style="margin-right: 20px">$balance_due_label</span> 
            <span class="text-info"> $balance_due</span><br>
            
             <p>
                 <span style="margin-right: 20px"> $customer_balance_label </span>  $customer_balance<br>
                <span style="margin-right: 20px"> $customer_paid_to_date_label </span>  $customer_paid_to_date<br>
            </p>
        </div>
    </div>
</div>
<div class="px-4 mt-4" style="width: 100%">
    <div class="inline-block" style="width: 70%">
        <p class="font-weight-bold">$terms_label</p>
        $terms
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

    public function task_table()
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
        return '
          <div style="width: 100%; margin-left: 20px">
             <div style="width: 45%" class="inline-block mb-2">
               $signature_here
           </div>
           
            <div style="width: 45%" class="inline-block mb-2">
               $client_signature_here
           </div>
</div>
        
        <div class="footer_class py-4 px-4" style="page-break-inside: avoid;">
        </div>';
    }

}
