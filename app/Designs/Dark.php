<?php

namespace App\Designs;

class Dark
{

    public function includes()
    {
        return '<title>$number</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
$css_link
<style>
    $custom_css
</style>';
    }


    public function header()
    {

        return '<div class="py-4 px-4">
<div class="border-4 border-dark mb-4">
    <div class="inline-block mt-2" style="margin-bottom: 15px; width: 60%">
        $company_logo
    </div>
    <div class="inline-block text-right" style="width: 30%">
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
<div class="inline-block border-right border-dashed border-dark pt-4" style="width: 40%; marin-top: 60px;">
    $client_details
</div>

<div class="inline-block pl-4" style="width: 50%">
 $company_details
$company_address
</div>
   
    
</div>
</div>

</div>

$table_here

<div class="mt-2 px-4 pb-4">
    <div class="inline-block" style="width: 50%">
        <div>
            <p>$entity.public_notes</p>
        </div>
    </div>
    <div class="inline-block" style="width: 40%">
        <div class="px-3 mt-2">
            <section class="inline-block col-6 text-right">
                $discount_label
                $total_tax_labels
                $line_tax_labels
            </section>
            <section class="inline-block col-6 text-right">
                $discount
                $total_tax_values
                $line_tax_values
            </section>
        </div>
    </div>
</div>
<div class="mt-4 pb-4 px-4">
    <div class="inline-block" style="width: 50%">
        <div>
            <p class="font-weight-bolder">$terms_label</p>
            <p>$terms</p>
        </div>
    </div>
    <div class="inline-block" style="width: 40%">
        <section class="py-2 text-success border-top border-bottom border-dashed border-dark px-2 mt-1">
            <p class="w-1/2">$balance_due_label</p>
            <p class="text-right col-6">$balance_due</p>
        </section>
    </div>
</div>
        <div class="border-bottom-4 ml-4 border-dark mt-4">
        <h4 class="font-weight-bolder mb-4">Thanks</h4>
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
