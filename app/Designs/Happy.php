<?php

namespace App\Designs;

class Happy
{

    public function header()
    {

        return '<div class="">
<div class="mt-4">
    <div class="inline-block col-6 ml-4" style="width: 40%">
        $account_logo
    </div>
    <div class="inline-block bg-info p-4" style="width: 40%">
        <div>
            <div class="text-white mr-4">
                $entity_labels
            </div>
            <div class="text-right text-white">
                $entity_details
            </div>
        </div>
    </div>
</div>';

    }

    public function body()
    {

        return '<div class="mt-4 border-dashed border-top-4 border-bottom-4 border-info">
<div class="inline-block" style="width: 50%">
    <div>
        <p class="font-weight-bold bg-info pl-4">$entity_label</p>
        <div class="py-4 mt-4 pl-4">
            <section>
                $customer_details
            </section>
        </div>
    </div>
</div>
<div class="inline-block col-6 ml-4" style="width: 40%">
    <div>
        <p class="font-weight-bold text-info pl-4">$from_label:</p>
        <div class="border-dashed border-top-4 border-bottom-4 border-info py-4 mt-2 pl-4">
            <section>
                $account_details
            </section>
        </div>
    </div>
</div>
</div>

$table_here

<div class="mt-3 px-4">
<div class="inline-block col-6" style="width: 70%">
    <div>
        <p>$entity.public_notes</p>
    </div>
</div>
<div class="inline-block" style="width: 30%">
    <div class="px-3 mt-2">
        <div class="col-6 text-right">
            <span style="margin-right: 20px"> $discount_label </span> $discount <br>
            <span style="margin-right: 20px"> $tax_label </span> $tax <br>
        </div>
    </div>
</div>
</div>
<div class="w-100 mt-4 pb-4 px-4 mt-2">
<div class="inline-block" style="width: 70%">
    <div>
        <p class="font-weight-bold">$terms_label</p>
        <p>$terms</p>
    </div>
</div>
<div class="inline-block" style="width: 30%">
    <section class="bg-info py-2 px-3 pt-4 text-white">
        <p class="text-right">$balance_due_label</p>
        <p class="text-right">$balance_due</p>
    </section>
</div>
</div>
</div>
</div>
';

    }

    public function table()
    {
        return '<table class="w-100">
<thead class="text-left bg-info rounded">
    $product_table_header
</thead>
<tbody>
    $product_table_body
</tbody>
</table>';
    }

     public function task_table()
    {
        
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
