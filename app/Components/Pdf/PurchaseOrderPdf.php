<?php


namespace App\Components\Pdf;


use ReflectionClass;
use ReflectionException;

class PurchaseOrderPdf extends PdfBuilder
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
        $contact === null ? $this->entity->company->contacts->first() : $contact;
        $company = $this->entity->company;

        $this->buildContact($contact)
             ->setTaxes($company)
             ->setDate($this->entity->date)
             ->setDueDate($this->entity->due_date)
             ->setNumber($this->entity->number)
             ->setPoNumber($this->entity->po_number)
             ->buildCompany($company)
             ->buildCompanyAddress($company)
             ->buildAccount($this->entity->account)
             ->setTerms($this->entity->terms)
             ->setFooter($this->entity->footer)
             ->setDiscount($company, $this->entity->discount_total)
             ->setShippingCost($company, $this->entity->shipping_cost)
             ->setVoucherCode(isset($this->entity->voucher_code) ? $this->entity->voucher_code : '')
             ->setSubTotal($company, $this->entity->sub_total)
             ->setBalance($company, $this->entity->balance)
             ->setTotal($company, $this->entity->total)
             ->setNotes($this->entity->public_notes)
            //->setInvoiceCustomValues()
             ->buildProduct()
             ->transformLineItems($company, $this->entity)
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
}
