<?php

namespace App\Designs;

class Simple
{
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
    $pdf_type
</h1>
<div class="border-bottom border-secondary"></div>
<div class="ml-4 mb-4">
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
<div class="border-bottom border-secondary"></div>

$table_here

<div class="px-4" style="width: 100%; margin-top: 80px;">
    <div class="inline-block" style="width: 80%">
        $entity.public_notes
    </div>
    $costs
</div>
    <div class="px-4 mt-4" style="width: 100%">
        <div class="inline-block" style="width: 80%">
            <p class="font-weight-bold">$terms_label</p>
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

    public function totals()
    {
        return '<div class="inline-block" style="width: 20% !important;">
        <div class="col-6 text-left">
            <span style="margin-right: 20px"> $discount_label </span> $discount <br>
            <span style="margin-right: 20px"> $tax_label </span> $tax<br>
            <span style="margin-right: 20px"> $shipping_cost_label </span> $shipping_cost
            <span style="margin-right: 20px"> $voucher_label </span> $voucher
            
             <p>
                 <span style="margin-right: 20px"> $customer_balance_label </span>  $customer_balance<br>
                <span style="margin-right: 20px"> $customer_paid_to_date_label </span>  $customer_paid_to_date<br>
            </p>
        </div>
    </div>';
    }

    public function table()
    {
        return '
        <table class="w-100 col-6 table-auto mt-4">
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

        </div>
</footer>
</body>
</html>';
    }

}
