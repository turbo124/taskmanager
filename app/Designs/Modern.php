<?php

namespace App\Designs;

class Modern
{

    public function __construct()
    {
    }


    public function includes()
    {
        return '<title>$number</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
$css_link
<style>
 $custom_css
</style>
</head>
<body>';
    }


    public function header()
    {

        return '<div class="header_class bg-warning" style="page-break-inside: avoid;">
<div class="inline-block ml-3" style="width: 50%">
	<h1 class="text-white font-weight-bold">$company.name</h1>
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
		$company_logo
	</div>
    <div class="inline-block" style="width: 40%">
		$client_details
    </div>
</div>
<div class="px-4 pt-4 pb-4">

$table_here

<div class="px-4 mt-4 w-full" style="page-break-inside: avoid; width: 100%">
			        <div class="inline-block" style="width: 60%">
			            $entity.public_notes
			        </div>
			        <div class="inline-block" style="page-break-inside: avoid; width: 30%">
			            <div class="inline-block col-6 text-right" style="page-break-inside: avoid;">
			            	$discount_label
			                $total_tax_labels
			                $line_tax_labels
			            </div>
			            <div class="inline-block col-6 text-right" style="page-break-inside: avoid;">
			            	$discount
			                $total_tax_values
			                $line_tax_values
			            </div>
			        </div>
			    </div>
			    <div class="px-4 mt-4 mt-4" style="page-break-inside: avoid; width: 100%">
			        <div style="page-break-inside: avoid; width: 60%">
			            <p class="font-weight-bolder">$terms_label</p>
			            $terms
			        </div>
			    </div>
			    <div class="mt-4 px-4 py-2 bg-secondary text-white" style="page-break-inside: avoid; width: 100%">
			        <div class="inline-block" style="width: 60%"></div>
			        <div class="inline-block w-auto" style="page-break-inside: avoid; width: 30%" >
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
    <thead class="text-left text-white bg-secondary display: table-header-group;">
       $product_table_header
    </thead>
    <tbody>
            $product_table_body
    </tbody>
</table>';
    }

    public function getTaskTable()
    {
        return '
<table class="w-100 table-auto mt-4">
    <thead class="text-left text-white bg-secondary display: table-header-group;">
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

        return '<div class="footer_class bg-warning py-4 px-4 pt-4" style="page-break-inside: avoid; width: 100%">
			    <div class="inline-block" style="width: 10%">
			        <!-- // -->
			    </div>
			    <div class="inline-block mt-2" style="width: 70%">
			        <div class="inline-block text-white" style="width: 40%">
			            $company_details
			        </div>
			        <div class="inline-block text-left text-white" style="width: 40%">
			            $company_address
			        </div>
			    </div>
			</div>
               
		
			</html>
		';

    }

}
