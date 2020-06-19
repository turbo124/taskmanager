<?php

namespace App\Designs;

class Warning
{

    public function header()
    {
        return '<div class="header_class bg-warning" style="page-break-inside: avoid;">
<div class="inline-block ml-3" style="width: 50%">
	<h1 class="text-white font-weight-bold">$account.name</h1>
</div>
<div class="inline-block mt-3 mb-3" style="width: 40%">
	<div class="inline-block text-white">
		$entity_labels
	</div>
	<div class="inline-block text-left text-white">
		$entity_details
	</div>
</div>
</div>';
    }

    public function body()
    {
        return '<table class="container"><thead><tr><td><div class="header-space"></div></td></tr></thead>
<tbody><tr><td>
<div class="px-4 pt-4" style="width: 100%">
    <div class="inline-block p-3" style="width: 50%">
		$account_logo
	</div>
    <div class="inline-block" style="width: 40%">
		$customer_details
    </div>
</div>
<div class="px-4 pt-4 pb-4">

$table_here

<div class="px-4 mt-4 w-100" style="page-break-inside: avoid;">
			        <div class="inline-block" style="width: 70%">
			            $entity.public_notes
			        </div>
			        <div class="inline-block" style="page-break-inside: avoid; width: 20%">
			            <div class="inline-block col-6 text-left" style="page-break-inside: avoid;">
			            	<span style="margin-right: 20px">$discount_label</span> $discount <br>
			                <span style="margin-right: 20px">$tax_label</span> $tax <br>
			                <span style="margin-right: 20px">$shipping_cost_label</span> $shipping_cost <br>
			                <span style="margin-right: 20px">$voucher_label</span> $voucher <br>
			            </div>
			        </div>
			    </div>
			    <div class="px-4 mt-4 mt-4" style="page-break-inside: avoid; width: 100%">
			        <div style="page-break-inside: avoid; width: 70%">
			            <p class="font-weight-bold">$terms_label</p>
			            $terms
			        </div>
			    </div>
			    <div class="mt-4 px-4 py-2 bg-secondary text-white" style="page-break-inside: avoid; width: 100%">
			        <div class="inline-block" style="width: 70%"></div>
			        <div class="" style="page-break-inside: avoid; width: 20%" >
			            <div style="page-break-inside: avoid;">
			                <p class="font-weight-bold">$balance_due_label</p>
			            </div>
			            <p>$balance_due</p>
			        </div>
			    </div>
</div>
</td></tr></tbody><tfoot><tr><td><div class="footer-space"></div></td></tr></tfoot></table>
';
    }

    public function table()
    {
        return '<table class="w-100 table-auto mt-4">
    <thead class="text-left text-white bg-secondary">
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
<table class="w-100 table-auto mt-4">
    <thead class="text-left text-white bg-secondary">
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
             <div style="width: 45%;" class="inline-block mb-2">
               $signature_here
           </div>
           
            <div style="width: 45%" class="inline-block mb-2">
               $client_signature_here
           </div>
</div>
		
		<div class="footer_class bg-warning py-4 px-4 pt-4" style="page-break-inside: avoid; width: 100%"> 

             <div class="inline-block" style="width: 10%">
			        <!-- // -->
			    </div>
			    <div class="inline-block mt-2" style="width: 70%">
			        <div class="inline-block text-white" style="width: 40%">
			            $account_details
			        </div>
			        <div class="inline-block text-left text-white" style="width: 40%">
			            $account_address
			        </div>
			    </div>
			</div>
               
		
			</html>
		';
    }

}
