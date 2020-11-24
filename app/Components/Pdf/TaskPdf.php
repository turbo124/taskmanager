<?php


namespace App\Components\Pdf;


use ReflectionClass;
use ReflectionException;

class TaskPdf extends PdfBuilder
{
    protected $entity;

    /**
     * TaskPdf constructor.
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
             ->buildCustomer($customer)
             ->buildCustomerAddress($customer)
             ->buildAccount($this->entity->account)
             ->setTerms($this->entity->terms)
             ->setFooter($this->entity->footer)
             ->setNotes($this->entity->public_notes)
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