<?php


namespace App\Components\Pdf;


use App\Models\Customer;
use App\Models\Transaction;
use ReflectionClass;
use ReflectionException;

class StatementPdf extends PdfBuilder
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
        $contact === null ? $this->entity->contacts->first() : $contact;
        $customer = $this->entity;

        $this->setDefaults($customer)
             ->buildContact($contact)
             ->buildCustomer($customer)
             ->buildCustomerAddress($customer)
             ->buildAccount($this->entity->account)
             ->setTerms($this->entity->terms)
             ->setFooter($this->entity->footer)
             ->setNotes($this->entity->public_notes)
             ->buildStatement();

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

    public function buildTable($columns)
    {
        $labels = $this->getLabels();
        $values = $this->getValues();

        $table = new \stdClass();

        $table->header = '<tr>';
        $table->body = '';
        $table_row = '<tr>';

        $translations = [
            '$amount'           => trans('texts.amount'),
            '$original_balance' => trans('texts.original_balance'),
            '$new_balance'      => trans('texts.new_balance'),
            '$date'             => trans('texts.date'),
            '$type'             => trans('texts.type')
        ];

        foreach ($columns as $key => $column) {
            $table->header .= '<td class="table_header_td_class">' . $translations[$column] . '</td>';
            $table_row .= '<td class="table_header_td_class">' . $column . '</td>';
        }

        $table_row .= '</tr>';

        $transactions = Transaction::where('customer_id', $this->entity->id)->orderBy('created_at', 'desc')->get();

        foreach ($transactions as $key => $transaction) {
            $item = [
                '$amount'           => $transaction->amount,
                '$original_balance' => $transaction->original_customer_balance,
                '$new_balance'      => $transaction->updated_balance,
                '$date'             => $this->formatDate($this->entity, $transaction->created_at),
                '$type'             => $transaction->transactionable_type
            ];

            $tmp = strtr($table_row, $item);
            $tmp = strtr($tmp, $values);

            $table->body .= $tmp;
        }

        $table->header .= '</tr>';

        $table->header = strtr($table->header, $labels);

        return $table;
    }
}