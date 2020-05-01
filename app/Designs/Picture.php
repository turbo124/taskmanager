<?php

namespace App\Designs;

class Picture
{

    public function header()
    {

        return '<div class="px-4 py-4">
<div class="mt-4">
    <div class="inline-block" ref="logo" style="width: 50%">
        $account_logo
    </div>
    <div class="inline-block" style="width: 40%">
        <div class="inline-block-col mr-4">
            $entity_labels
        </div>
        <div class="inline-block text-right">
            $entity_details
        </div>
    </div>
</div>
</div>';

    }

    public function body()
    {

        return '<div class="bg-secondary h-auto p-4 pt-4">
<div>
    <div class="inline-block mr-4" style="width: 40%">
        <p class="text-uppercase text-warning">$to_label:</p>
        <div class="ml-4 mt-4 text-white">
            $customer_details
        </div>
    </div>
    <div class="inline-block" style="width: 30%">
        <p class="text-uppercase text-warning">$from_label:</p>
        <div class="ml-4 text-white">
            $account_details
        </div>
    </div>
</div>
</div>

$table_here

<div class="mt-2 px-4 pb-4">
<div class="inline-block mt-4" style="width: 70%">
    <div>
        <p>$entity.public_notes</p>
    </div>
</div>
<div class="inline-block" style="width: 20%">
    <div class="px-3 mt-2">
        <div class="inline-block col-6 text-right">
            <span style="margin-right: 20px">$discount_label</span> $discount<br>
            <span style="margin-right: 20px">$tax_label</span> $tax<br>
        </div>
    </div>
</div>
</div>
<div class="mt-4 pb-4 px-4">
<div class="inline-block" style="width: 70%">
    <div>
        <p class="font-weight-bold">$terms_label</p>
        <p>$terms</p>
    </div>
</div>
<div class="inline-block" style="width: 20%">
    <section class="bg-warning py-2 pt-4 pr-4 text-white px-2 mt-1">
        <p class="text-right">$balance_due_label</p>
        <p class="text-right">$balance_due</p>
    </section>
</div>
</div>
</div>
</div>';

    }

    public function table()
    {
        return '<table class="w-100 table-auto">
<thead class="text-left border-bottom-4 border-dark">
    $product_table_header
</thead>
<tbody>
    $product_table_body
</tbody>
</table>';
    }

    public function task_table()
    {
        return '
<table class="w-100 table-auto">
<thead class="text-left border-bottom-4 border-dark">
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
