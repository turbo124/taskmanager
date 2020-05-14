<?php

namespace App\Designs;

class Dark
{

    public function header()
    {
        return '<div class="py-4 px-4">
<div class="border-4 border-dark mb-4">
    <div class="inline-block mt-2" style="margin-bottom: 15px; width: 60%; margin-top: 20px;">
        $account_logo
    </div>
    <div class="inline-block text-right" style="width: 40%; margin-top: 20px">
        <div class="inline-block mr-4">
            $entity_labels
        </div>
        <div class="inline-block text-right">
            $entity_details
        </div>
    </div>
</div>
<div class="border-bottom border-dark mt-1"></div>';
    }

    public function body()
    {
        return '<div class="pt-4">
<div class="inline-block border-right border-dashed border-dark pt-4" style="width: 40%; margin-left: 40px;">
    $customer_details
</div>

<div class="inline-block pl-4" style="width: 20%">
 $account_details
$account_address
</div>
   
    
</div>
</div>

</div>

$table_here

<div class="mt-2 px-4 pb-4">
    <div class="inline-block" style="width: 70%">
        <div>
            <p>$entity.public_notes</p>
        </div>
    </div>
    <div class="inline-block" style="width: 20%">
        <div class="px-3 mt-2">
            <div class="inline-block col-6 text-left">
                <span style="margin-right: 20px"> $discount_label </span> $discount<br>
                <span style="margin-right: 20px">$tax_label </span> $tax
            </div>
        </div>
    </div>
</div>
<div class="mt-1 pb-4 px-4">
    <div class="inline-block" style="width: 70%">
        <div>
            <p class="font-weight-bold">$terms_label</p>
            <p>$terms</p>
        </div>
    </div>
    <div class="inline-block" style="width: 20%">
        <section class="py-2 pt-4 text-success border-top border-bottom border-dashed border-dark px-2 mt-1">
            <p class="text-right">$balance_due_label</p>
            <p class="text-right">$balance_due</p>
        </section>
    </div>
</div>
        <div class="border-bottom-4 ml-4 border-dark mt-4">
        <h4 class="font-weight-bold mb-4">Thanks for shopping with us</h4>
    </div>
    <div class="border-bottom border-dark mt-1"></div>
</div>

';
    }

    public function table()
    {
        return '<table class="w-100 table-auto mb-4 mt-4">
    <thead class="text-left border-dashed border-bottom border-dark">
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
