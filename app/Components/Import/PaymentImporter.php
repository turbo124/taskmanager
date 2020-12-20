<?php


namespace App\Components\Import;


use App\Factory\InvoiceFactory;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use App\Repositories\InvoiceRepository;
use App\Transformations\InvoiceTransformable;

class PaymentImporter extends BaseCsvImporter
{
    use ImportMapper;

    private array $export_columns = [
        'number'        => 'Number',
        'customer_id'   => 'Customer name',
        'date'          => 'Date',
        'transaction_reference'        => 'Transaction Reference',
        'amount' => 'Amount',
        'payment_type' => 'Payment Type'
    ];

    /**
     * @var array|string[]
     */
    private array $mappings = [
        'number'        => 'number',
        'customer name' => 'customer_id',
        'date'          => 'date',
        'amount'        => 'amount',
        'transaction reference'      => 'transaction_reference',
        'payment type'         => 'payment_type_id',
        'public notes'  => 'public_notes',
        'private notes' => 'private_notes'
    ];

    /**
     * @var Account
     */
    private Account $account;

    /**
     * @var User
     */
    private User $user;

    /**
     * @var Customer
     */
    private Customer $customer;

    /**
     * @var Export
     */
    private Export $export;

    /**
     * InvoiceImporter constructor.
     * @param Account $account
     * @param User $user
     * @throws CsvImporterException
     */
    public function __construct(Account $account, User $user)
    {
        parent::__construct();

        $this->account = $account;
        $this->user = $user;
        $this->export = new Export($this->account, $this->user);
    }

    /**
     *  Specify mappings and rules for the csv importer, you also may create csv files to write csv entities
     *  and overwrite global configurations
     *
     * @return array
     */
    public function csvConfigurations()
    {
        return [
            'mappings' => [
                'customer name' => ['validation' => 'required', 'cast' => 'string'],
                'amount'         => ['validation' => 'required', 'cast' => 'string'],
                'private notes' => ['cast' => 'string'],
                'public notes'  => ['cast' => 'string'],
                'date'          => ['validation' => 'required', 'cast' => 'date'],
                //'customer_id' => ['required', 'cast' => 'int'],
            ],
            'config'   => [
                'csv_date_format' => 'Y-m-d'
            ]
        ];
    }

    /**
     * @param array $params
     * @return Invoice
     */
    public function factory(array $params): ?Invoice
    {
        if (empty($this->customer)) {
            return null;
        }

        return PaymentFactory::create($this->account, $this->user);
    }

    /**
     * @return PaymentRepository
     */
    public function repository(): PaymentRepository
    {
        return new PaymentRepository(new Payment());
    }

    public function export($is_json = false)
    {
        $export_columns = $this->getExportColumns();
        $list = Invoice::where('account_id', '=', $this->account->id)->get();

        $payments = [];

        foreach ($list as $invoice) {
            $arr_invoice = $this->transformObject($invoice);

            foreach ($invoice->line_items as $line_item) {
                $invoices[] = array_merge($arr_invoice, (array)$line_item);
            }
        }

        if ($is_json) {
            return json_encode($payments);
        }

        $this->export->build(collect($payments), $export_columns);

        return true;
    }

    public function getExportColumns()
    {
        return $this->export_columns;
    }

    public function transformObject($object)
    {
        return (new PaymentTransformable())->transformPayment($object);
    }

    public function getContent()
    {
        return $this->export->getContent();
    }

    public function getTemplate()
    {
        return asset('storage/templates/payments.csv');
    }
}
