<?php


namespace App\Components\Import;


use App\Factory\LeadFactory;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\User;
use App\Repositories\LeadRepository;

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
                'first_name' => ['required', 'cast' => 'string'],
                'last_name'         => ['cast' => 'string'],
                'email' => ['cast' => 'string'],
                'phone'  => ['cast' => 'string'],
                'website'     => ['cast' => 'string'],
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
     * @return Lead
     */
    public function factory(array $params): ?Lead
    {
       return LeadFactory::create($this->account, $this->user);
    }

    /**
     * @return LeadRepository
     */
    public function repository(): LeadRepository
    {
        return new LeadRepository(new Lead());
    }

    public function transformObject($object)
    {
        return $this->transform();
    }

    public function customHandler()
    {
    }
}
