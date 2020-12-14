<?php


namespace App\Components\Pdf;


use App\Models\Invoice;
use ReflectionClass;
use ReflectionException;
use stdClass;

class InvoicePdf extends PdfBuilder
{
    protected $entity;

    /**
     * InvoicePdf constructor.
     * @param $entity
     * @throws ReflectionException
     */
    public function __construct($entity)
    {
        parent::__construct($entity);
        $this->entity = $entity;
        $this->class = strtolower((new ReflectionClass($this->entity))->getShortName());
    }

    public function build($contact = null)
    {
        $contact === null ? $this->entity->customer->contacts->first() : $contact;
        $customer = $this->entity->customer;

        $this->setDefaults($customer)
             ->buildContact($contact)
             ->setTaxes($customer)
             ->setDate($this->entity->date)
             ->setDueDate($this->entity->due_date)
             ->setNumber($this->entity->number)
             ->setPoNumber($this->entity->po_number)
             ->buildCustomer($customer)
             ->buildCustomerAddress($customer)
             ->buildAccount($this->entity->account)
             ->setTerms($this->entity->terms)
             ->setFooter($this->entity->footer)
             ->setDiscount($customer, $this->entity->discount_total)
             ->setShippingCost($customer, $this->entity->shipping_cost)
             ->setVoucherCode(isset($this->entity->voucher_code) ? $this->entity->voucher_code : '')
             ->setSubTotal($customer, $this->entity->sub_total)
             ->setBalance($customer, $this->entity->balance)
             ->setTotal($customer, $this->entity->total)
//             ->setCustomerBalance($customer)
//             ->setCustomerPaidToDate($customer)
             ->setNotes($this->entity->public_notes)
             ->setInvoiceCustomValues()
             ->buildProduct()
             ->transformLineItems($customer, $this->entity)
             ->buildTask();

        foreach ($this->data as $key => $value) {
            if (isset($value['label'])) {
                $this->labels[$key . '_label'] = $value['label'];
            }

            if (isset($value['value'])) {
                $this->values[$key] = $value['value'];
            }
        }

        return $this;
    }

    /**
     * @param $columns
     * @return array|stdClass
     */
    public function buildTable($columns)
    {
        $header = '';
        $table_row = '';

        $labels = $this->getLabels();
        $values = $this->getValues();

        foreach ($columns as $key => $column) {
            $header .= '<td>' . $labels[$column . '_label'] . '</td>';
            $table_row .= '<td class="table_header_td_class">' . $column . '</td>';
        }

        if (empty($this->line_items)) {
            return [];
        }

        $table_structure = [
            Invoice::PRODUCT_TYPE => [
                'header' => '',
                'body'   => ''
            ],
            Invoice::TASK_TYPE    => [
                'header' => '',
                'body'   => ''
            ],
            Invoice::EXPENSE_TYPE => [
                'header' => '',
                'body'   => ''
            ]
        ];

        $types = array_keys($table_structure);

        foreach ($types as $type) {
            if (!empty($this->line_items[$type])) {
                $table_structure[$type]['header'] .= '<tr>' . strtr($header, $labels) . '</tr>';

                foreach ($this->line_items[$type] as $data) {
                    $tmp = strtr($table_row, $data);
                    $tmp = strtr($tmp, $values);

                    $table_structure[$type]['body'] .= '<tr>' . $tmp . '</tr>';
                }
            }
        }

        return $table_structure;
    }

    protected function buildObject()
    {
        die('here');
    }
}