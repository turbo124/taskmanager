<?php


namespace App\Components\Import;


use App\Factory\DealFactory;
use App\Models\Account;
use App\Models\Deal;
use App\Models\User;
use App\Repositories\DealRepository;
use App\Transformations\DealTransformable;

class DealImporter extends BaseCsvImporter
{
    use ImportMapper;
    use DealTransformable;

    /**
     * @var array|string[]
     */
    private array $mappings = [
        'name'          => 'name',
        'description'   => 'description',
        'valued_at'     => 'valued_at',
        'due_date'      => 'due_date',
        'terms'         => 'terms',
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
                'name'        => ['required', 'cast' => 'string'],
                'description' => ['cast' => 'string'],
                'valued_at'   => ['cast' => 'string'],
                'due_date'    => ['cast' => 'date'],
                //'customer_id' => ['required', 'cast' => 'int'],
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
        return DealFactory::create($this->user, $this->account);
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
        return $this->transformDeal($object);
    }

    public function customHandler()
    {
    }
}
