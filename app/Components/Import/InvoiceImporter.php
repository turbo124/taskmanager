<?php


namespace App\Components\Import;


use App\Factory\InvoiceFactory;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use App\Repositories\InvoiceRepository;
use App\Transformations\InvoiceTransformable;

class InvoiceImporter extends BaseCsvImporter
{
    use ImportMapper;

    /**
     * @var array|string[]
     */
    private array $mappings = [
        'number'        => 'number',
        'customer_name' => 'customer_id',
        'date'          => 'date',
        'po number'     => 'po_number',
        'due date'      => 'due_date',
        'terms'         => 'terms',
        'public notes'  => 'public_notes',
        'private notes' => 'private_notes',
        'line_items'    => [
            'description'   => 'description',
            'product'       => 'product_id',
            'unit_price'    => 'unit_price',
            'unit_discount' => 'unit_discount',
            'unit_tax'      => 'unit_tax',
            'quantity'      => 'quantity',
        ],
        'shipping_cost' => 'shipping_cost',
        'tax_rate'      => 'tax_rate'
    ];

    private $repository = InvoiceRepository::class;

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
            'mappings'  => [
                'customer_name' => ['required', 'cast' => 'string'],
                'terms'         => ['cast' => 'string'],
                'private notes' => ['cast' => 'string'],
                'public notes'  => ['cast' => 'string'],
                'po number'     => ['cast' => 'string'],
                'date'          => ['required', 'cast' => 'date'],
                'due date'      => ['cast' => 'date'],
                //'customer_id' => ['required', 'cast' => 'int'],
            ],
            'csv_files' => [
                'valid_entities'   => '/valid_entities.csv',
                'invalid_entities' => '/invalid_entities.csv',
            ],
            'config'    => [
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

        return InvoiceFactory::create($this->account, $this->user, $this->customer);
    }

    /**
     * @return InvoiceRepository
     */
    public function repository(): InvoiceRepository
    {
        return new InvoiceRepository(new Invoice());
    }

    public function transformObject($object)
    {
        return (new InvoiceTransformable())->transformInvoice($object);
    }

    public function customHandler()
    {
    }
}
