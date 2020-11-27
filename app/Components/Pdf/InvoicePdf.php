<?php


namespace App\Components\Pdf;


use ReflectionClass;
use ReflectionException;

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
     * @return array|\stdClass
     */
    public function buildTable($columns)
    {
        $labels = $this->getLabels();
        $values = $this->getValues();

        $table = new \stdClass();

        $table->header = '<tr>';
        $table->body = '';
        $table_row = '<tr>';

        foreach ($columns as $key => $column) {
            $table->header .= '<td class="table_header_td_class">' . $column . '_label</td>';
            $table_row .= '<td class="table_header_td_class">' . $column . '</td>';
        }

        $table_row .= '</tr>';

        if (empty($this->line_items)) {
            return [];
        }

        foreach ($this->line_items as $key => $item) {
            $tmp = strtr($table_row, $item);
            $tmp = strtr($tmp, $values);
            $table->body .= $tmp;
        }

        $table->header .= '</tr>';

        $table->header = strtr($table->header, $labels);

        return $table;
    }

    protected function buildObject()
    {
        die('here');
    }
}