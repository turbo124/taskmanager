<?php


namespace App\Components\Import;


use App\Factory\DealFactory;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\User;
use App\Repositories\DealRepository;

class DealImporter extends BaseCsvImporter
{
    use ImportMapper;

    /**
     * @var array|string[]
     */
    private array $mappings = [
        'name'        => 'name',
        'description' => 'description'
        'valued_at'          => 'valued_at',
        'po number'     => 'po_number',
        'due date'      => 'due_date',
        'terms'         => 'terms',
        'public notes'  => 'public_notes',
        'private notes' => 'private_notes'
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
                'name' => ['required', 'cast' => 'string'],
                'description'         => ['cast' => 'string'],
                'valued_at' => ['cast' => 'string'],
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
     * @return Deal
     */
    public function factory(array $params): ?Deal
    {
       return DealFactory::create($this->account, $this->user);
    }

    /**
     * @return DealRepository
     */
    public function repository(): DealRepository
    {
        return new DealRepository(new Deal());
    }

    public function transformObject($object)
    {
        return $this->transform();
    }

    public function customHandler()
    {
    }
}
