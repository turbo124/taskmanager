<?php

namespace App\Designs;

class Jazzy
{

    public function header()
    {

        return '<div class="px-4 py-4">
<div class="mt-4 mb-4" style="width: 100%">
    <div class="inline-block border-left pl-2 border-dark mr-4 mt-4" style="width: 30%">
        <p class="font-weight-bold text-uppercase text-info">From:</p>
        <div>
            <div class="mr-5">
                $account_details
            </div>
            <div>
                $account_address
            </div>
        </div>
    </div>
    <div class="border-left pl-4 border-dark inline-block" style="width: 30%">
        <p class="font-weight-bold text-uppercase text-info">To:</p>
        $customer_details
    </div>
    <div class="inline-block mt-4 h-16" style="width: 30%">
        $account_logo
    </div>
</div>';

    }

    public function body()
    {

        return '<div class="mx-4 mt-4">
<h1 class="font-weight-bold text-uppercase">$entity_label</h1>
<div class="mt-1">
    <span class="font-weight-bold text-uppercase text-info">$entity_number</span>
    <div class="inline-block ml-4">
        <span class="text-uppercase">$date_label</span>
        <span>$date</span>
    </div>
    <div class="inline-block ml-4">
        <span class="text-uppercase">$due_date_label</span>
        <span>$due_date</span>
    </div>
    <div class="inline-block ml-4">
        <span class="text-uppercase">$balance_due_label</span>
        <span class="text-info">$balance_due</span>
    </div>
</div>
</div>

$table_here

<div class="mt-4">
<div class="inline-block" style="width: 70%">
    <div>
        <p>$entity.public_notes</p>
        <div class="pt-4">
            <p class="font-weight-bold">$terms_label</p>
            <p>$terms</p>
        </div>
    </div>
</div>
<div class="inline-block" style="width: 20%">
        <div class="col-6 text-left">
            <span style="margin-right: 60px">$discount_label</span> $discount<br>
            <span style="margin-right: 60px">$tax_label</span> $tax<br>
             <span style="margin-right: 60px">$balance_due_label</span> $balance_due<br>
        </div>
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

    public function task_table() {
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

        return '<div class="footer_class py-4 px-4" style="page-break-inside: avoid;">

  <div class="text-center">
               $signature_here
           </div>

      </div>
</body>
</html>';

    }

}
