<?php


namespace App\Components\Import;


use App\Factory\LeadFactory;
use App\Models\Account;
use App\Models\Lead;
use App\Models\User;
use App\Repositories\LeadRepository;
use App\Transformations\LeadTransformable;

class LeadImporter extends BaseCsvImporter
{
    use ImportMapper;
    use LeadTransformable;

    /**
     * @var array|string[]
     */
    private array $mappings = [
        'first_name'    => 'first_name',
        'last_name'     => 'last_name',
        'email'         => 'email',
        'phone'         => 'phone',
        'website'       => 'website',
        'terms'         => 'terms',
        'public notes'  => 'public_notes',
        'private notes' => 'private_notes',
        'job_title'     => 'job_title',
        'address_1'     => 'address_1',
        'address_2'     => 'address_2',
        'zip'           => 'zip',
        'city'          => 'city',
        'name'          => 'name',
        'description'   => 'description',
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
                'first_name'  => ['required', 'cast' => 'string'],
                'last_name'   => ['cast' => 'string'],
                'email'       => ['cast' => 'string'],
                'phone'       => ['cast' => 'string'],
                'website'     => ['cast' => 'string'],
                'address_1'   => ['required', 'cast' => 'string'],
                'address_2'   => ['required', 'cast' => 'string'],
                'zip'         => ['required', 'cast' => 'string'],
                'city'        => ['required', 'cast' => 'string'],
                'name'        => ['required', 'cast' => 'string'],
                'description' => ['required', 'cast' => 'string'],
                'job_title'   => ['cast' => 'string'],
                //'customer_id' => ['required', 'cast' => 'int'],
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
        return $this->transformLead($object);
    }

    public function customHandler()
    {
    }
}
