<?php

namespace App\Designs;

class Secondary
{

    public function header()
    {

        return '<div class="my-4 mx-4">
<div>
    <div class="inline-block" style="width: 10%">
        $account_logo
    </div>
    <div class="ml-4 inline-block" style="width: 80%">
        <div class="text-secondary">
            $account_details
        </div>
        <div class="text-secondary ml-4">
            $account_address
        </div>
    </div>
</div>';

    }

    public function body()
    {

        return '<div class="mt-4">
    <div class="inline-block" style="width: 40%">
        <span>$entity_label</span>
        <section class="text-warning mt-4">
            $customer_details
        </section>
    </div>
    <div class="inline-block col-6 ml-4 bg-warning px-4 py-4 rounded" style="width: 40%;">
        <div class="text-white">
            <section class="col-6">
                $entity_labels
            </section>
            <section class="">
                $entity_details
            </section>
        </div>
    </div>
</div>

$table_here

<div class="mt-4 px-4 pt-4 pb-4 bg-secondary rounded py-2 text-white" style="width: 100%">
    <div class="inline-block" style="width: 70%">
        <div>
            <p>$entity.public_notes</p>
        </div>
    </div>
    <div class="inline-block px-3 mt-1" style="width: 20%">
            <div class="col-6 text-left">
                <span style="margin-right: 40px">$discount_label </span>$discount <br>
                <span style="margin-right: 40px">$tax_label </span>$tax <br>
                <span style="margin-right: 40px">$balance_due_label </span>$balance_due <br>
            </div>
    </div>
</div>
<div class="mt-4 pb-4 px-4" style="width: 100%">
<div class="inline-block" style="width: 70%">
    <div class="">
        <p class="font-weight-bold">$terms_label</p>
        <p>$terms</p>
    </div>
</div>
</div>
</div>';

    }

    public function table()
    {
        return '
        <table class="w-100 table-auto mt-4">
    <thead class="text-left">
        $product_table_header
    </thead>
    <tbody>
        $product_table_body
    </tbody>
</table>';
    }

    public function task_table() {
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
