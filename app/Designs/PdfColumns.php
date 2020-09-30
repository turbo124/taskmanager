<?php

namespace App\Designs;

class PdfColumns
{
    public $design;

    protected $input_variables;

    protected $exported_variables;

    protected $entity_string;

    protected $entity;

    private $objPdf;

    private $default_columns = [
        'customer_details' => [
            '$customer.paid_to_date'      => '<span>$customer.paid_to_date_label: $customer.paid_to_date</span>',
            '$customer.balance'           => '<span>$customer.balance_label: $customer.balance</span><br>',
            '$customer.name'              => '$customer.name<br>',
            '$customer.number'            => '$customer.number<br>',
            '$customer.vat_number'        => '$customer.vat_number<br>',
            '$customer.address1'          => '$customer.address1<br>',
            '$customer.address2'          => '$customer.address2<br>',
            '$customer.city_state_postal' => '$customer.city_state_postal<br>',
            '$customer.postal_city_state' => '$customer.postal_city_state<br>',
            '$customer.country'           => '$customer.country<br>',
            '$contact.email'              => '$customer.email<br>',
            '$customer.custom1'           => '$customer.custom1<br>',
            '$customer.custom2'           => '$customer.custom2<br>',
            '$customer.custom3'           => '$customer.custom3<br>',
            '$customer.custom4'           => '$customer.custom4<br>',
            '$contact.contact1'           => '$contact.custom1<br>',
            '$contact.contact2'           => '$contact.custom2<br>',
            '$contact.contact3'           => '$contact.custom3<br>',
            '$contact.contact4'           => '$contact.custom4<br>',
        ],
        'account_details'  => [
            '$account.name'       => '<span>$account.name</span>',
            '$account.number'     => '<span>$account.number </span>',
            '$account.vat_number' => '<span>$account.vat_number </span>',
            '$account.website'    => '<span>$account.website </span>',
            '$account.email'      => '<span>$account.email </span>',
            '$account.phone'      => '<span>$account.phone </span>',
            '$account.account1'   => '<span>$account1</span>',
            '$account.account2'   => '<span>$account2</span>',
            '$account.account3'   => '<span>$account3</span>',
            '$account.account4'   => '<span>$account4</span>',
        ],
        'account_address'  => [
            '$account.address1'          => '<span>$account.address1 </span>',
            '$account.address2'          => '<span>$account.address2 </span>',
            '$account.city_state_postal' => '<span>$account.city_state_postal </span>',
            '$account.postal_city_state' => '<span>$account.postal_city_state </span>',
            '$account.country'           => '<span>$account.country </span>',
            '$account.account1'          => '<span>$account1 </span>',
            '$account.account2'          => '<span>$account2 </span>',
            '$account.account3'          => '<span>$account3 </span>',
            '$account.account4'          => '<span>$account4 </span>',
        ]
    ];

    private $entity_columns = [
        'order'   => [
            '$order.number'      => '<span>$order.number_label: $order.number</span>',
            '$order.po_number'   => '<span>$order.po_number_label: $order.po_number</span>',
            '$order.order_date'  => '<span>$order.date_label: $order.date</span>',
            '$order.due_date'    => '<span>$order.due_date_label: $order.due_date</span>',
            '$order.balance'     => '<span>$order.balance_due_label: $order.balance_due</span>',
            '$order.order_total' => '<span>$order.total_label: $order.total</span>',
            '$order.partial_due' => '<span>$order.partial_due_label: $order.partial_due</span>',
            '$order.custom1'     => '<span>$order1_label: $order.custom1</span>',
            '$order.custom2'     => '<span>$order2_label: $order.custom2</span>',
            '$order.custom3'     => '<span>$order3_label: $order.custom3</span>',
            '$order.custom4'     => '<span>$order4_label: $order.custom4</span>',
            '$surcharge1'        => '<span>$surcharge1_label: $surcharge1</span>',
            '$surcharge2'        => '<span>$surcharge2_label: $surcharge2</span>',
            '$surcharge3'        => '<span>$surcharge3_label: $surcharge3</span>',
            '$surcharge4'        => '<span>$surcharge4_label: $surcharge4</span>',
        ],
        'case'    => [

        ],
        'task'    => [

        ],
        'deal'    => [

        ],
        'lead'    => [

        ],
        'invoice' => [
            '$invoice.number'        => '<span>$invoice.number_label: $invoice.number</span>',
            '$invoice.po_number'     => '<span>$invoice.po_number_label: $invoice.po_number</span>',
            '$invoice.invoice_date'  => '<span>$invoice.date_label: $invoice.date</span>',
            '$invoice.due_date'      => '<span>$invoice.due_date_label: $invoice.due_date</span>',
            '$invoice.balance'       => '<span>$invoice.balance_due_label: $invoice.balance_due</span>',
            '$invoice.invoice_total' => '<span>$invoice.total_label: $invoice.total</span>',
            '$invoice.partial_due'   => '<span>$invoice.partial_due_label: $invoice.partial_due</span>',
            '$invoice.custom1'       => '<span>$invoice1_label: $invoice.custom1</span>',
            '$invoice.custom2'       => '<span>$invoice2_label: $invoice.custom2</span>',
            '$invoice.custom3'       => '<span>$invoice3_label: $invoice.custom3</span>',
            '$invoice.custom4'       => '<span>$invoice4_label: $invoice.custom4</span>',
            '$surcharge1'            => '<span>$surcharge1_label: $surcharge1</span>',
            '$surcharge2'            => '<span>$surcharge2_label: $surcharge2</span>',
            '$surcharge3'            => '<span>$surcharge3_label: $surcharge3</span>',
            '$surcharge4'            => '<span>$surcharge4_label: $surcharge4</span>',
        ],

        'quote' => [
            '$quote.number'      => '<span>$invoice.number_label: $invoice.number</span>',
            '$quote.po_number'   => '<span>$quote.po_number_label: $quote.po_number</span>',
            '$quote.quote_date'  => '<span>$quote.date_label: $quote.date</span>',
            '$quote.valid_until' => '<span>$quote.due_date_label: $quote.due_date</span>',
            '$quote.balance_due' => '<span>$quote.balance_due_label: $quote.balance_due</span>',
            '$quote.quote_total' => '<span>$quote.total_label: $quote.total</span>',
            '$quote.partial_due' => '<span>$quote.partial_due_label: $quote.partial_due</span>',
            '$quote.custom1'     => '<span>$quote1</span>',
            '$quote.custom2'     => '<span>$quote2</span>',
            '$quote.custom3'     => '<span>$quote3</span>',
            '$quote.custom4'     => '<span>$quote4</span>',
            '$quote.surcharge1'  => '<span>$surcharge1</span>',
            '$quote.surcharge2'  => '<span>$surcharge2</span>',
            '$quote.surcharge3'  => '<span>$surcharge3</span>',
            '$quote.surcharge4'  => '<span>$surcharge4</span>',
        ],

        'purchase_order' => [
            '$purchaseorder.number'      => '<span>$invoice.number_label: $invoice.number</span>',
            '$purchaseorder.po_number'   => '<span>$purchaseorder.po_number_label: $purchaseorder.po_number</span>',
            '$purchaseorder.quote_date'  => '<span>$purchaseorder.date_label: $purchaseorder.date</span>',
            '$purchaseorder.valid_until' => '<span>$purchaseorder.due_date_label: $purchaseorder.due_date</span>',
            '$purchaseorder.balance_due' => '<span>$purchaseorder.balance_due_label: $purchaseorder.balance_due</span>',
            '$purchaseorder.quote_total' => '<span>$purchaseorder.total_label: $purchaseorder.total</span>',
            '$purchaseorder.partial_due' => '<span>$purchaseorder.partial_due_label: $purchaseorder.partial_due</span>',
            '$purchaseorder.custom1'     => '<span>$purchaseorder</span>',
            '$purchaseorder.custom2'     => '<span>$purchaseorder</span>',
            '$purchaseorder.custom3'     => '<span>$purchaseorder</span>',
            '$purchaseorder.custom4'     => '<span>$purchaseorder</span>',
            '$purchaseorder.surcharge1'  => '<span>$surcharge1</span>',
            '$purchaseorder.surcharge2'  => '<span>$surcharge2</span>',
            '$purchaseorder.surcharge3'  => '<span>$surcharge3</span>',
            '$purchaseorder.surcharge4'  => '<span>$surcharge4</span>',
        ],

        'credit' => [
            '$credit.number'        => '<span>$credit.number_label: $credit.number</span>',
            '$credit.po_number'     => '<span>$credit.po_number_label: $credit.po_number</span>',
            '$credit.credit_date'   => '<span>$credit.date_label: $credit.date</span>',
            '$credit.credit_date'   => '<span>$credit.due_date_label: $credit.due_date</span>',
            '$credit.balance'       => '<span>$credit.balance_due_label: $credit.balance_due</span>',
            '$credit.credit_amount' => '<span>$credit.total_label: $credit.total</span>',
            '$credit.partial_due'   => '<span>$credit.partial_due_label: $quote.partial_due</span>',
            '$credit.custom1'       => '<span>$credit1</span>',
            '$credit.custom2'       => '<span>$credit2</span>',
            '$credit.custom3'       => '<span>$credit3</span>',
            '$credit.custom4'       => '<span>$credit4</span>',
            '$credit.surcharge1'    => '<span>$surcharge1</span>',
            '$credit.surcharge2'    => '<span>$surcharge2</span>',
            '$credit.surcharge3'    => '<span>$surcharge3</span>',
            '$credit.surcharge4'    => '<span>$surcharge4</span>',
        ]
    ];

    private $sections = ['header', 'body', 'table', 'footer'];

    public function __construct($objPdf, $entity, $design, $input_variables, $entity_string)
    {
        $this->entity = $entity;

        $this->objPdf = $objPdf;

        $this->design = $design->design;

        $this->input_variables = json_decode(json_encode($input_variables), true);

        $this->entity_string = $entity_string;
    }

    public function buildDesign(): bool
    {
        $this->process();
        $this->buildTables();

        return true;
    }

    private function process()
    {
        foreach ($this->default_columns as $key => $default) {
            $this->exported_variables['$' . $key] = $this->formatVariables(
                array_values($this->input_variables[$key]),
                $default
            );
        }

        $this->exported_variables['$entity_details'] = $this->formatVariables(
            array_values($this->input_variables[$this->entity_string]),
            $this->entity_columns[$this->entity_string],
            '<br>'
        );

        $this->exported_variables['$entity_labels'] = $this->formatVariables(
            array_keys($this->input_variables[$this->entity_string]),
            $this->entity_columns[$this->entity_string],
            'label'
        );

        return true;
    }

    private function formatVariables($values, $variables, $appends = '', $type = 'values'): string
    {
        $output = '';

        foreach ($values as $key => $value) {
            if (isset($variables[$value])) {
                $tmp = str_replace("</span>", "_label</span>", $variables[$value]);
                $output .= $type === 'label' ? $tmp : $variables[$value] . $appends;
                continue;
            }
        }

        return $output;
    }

    private function buildTables()
    {
        $this->objPdf->build();

        $task_columns = $this->getTableColumns();

        $product_table = $this->objPdf->buildTable(
            $task_columns,
            $this->design->product,
            'product'
        );

        $task_table = $this->objPdf->buildTable($task_columns, $this->design->task, 'task');

        $this->exported_variables['$task_table_header'] = '';
        $this->exported_variables['$product_table_header'] = '';
        $this->exported_variables['$product_table_body'] = '';
        $this->exported_variables['$task_table_body'] = '';


        if (!empty($product_table['product'])) {
            $this->exported_variables['$product_table_header'] = $product_table['product']->header;
            $this->exported_variables['$product_table_body'] = $product_table['product']->body;
        }

        if (!empty($task_table['task'])) {
            $this->exported_variables['$task_table_header'] = $task_table['task']->header;
            $this->exported_variables['$task_table_body'] = $task_table['task']->body;
        }

        return true;
    }

    private function getTableColumns()
    {
        switch ($this->entity_string) {
            case 'case':
                return $this->input_variables['case_columns'];
            case 'task':
                return $this->input_variables['task_columns'];
            case 'deal':
                return $this->input_variables['deal_columns'];
            default:
                return $this->input_variables['product_columns'];
        }
    }

    public function getSection($section): string
    {
        return str_replace(
            array_keys($this->exported_variables),
            array_values($this->exported_variables),
            $this->design->{$section}
        );
    }

    public function getDefaultColumns()
    {
        return $this->default_columns;
    }

}
