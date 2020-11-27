<?php


namespace App\Components\Import;


use App\Factory\CustomerFactory;
use App\Models\Account;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\User;
use App\Repositories\CustomerContactRepository;
use App\Repositories\CustomerRepository;
use App\Transformations\CustomerTransformable;

class CustomerImporter extends BaseCsvImporter
{
    use ImportMapper;
    use CustomerTransformable;

    /**
     * @var array|string[]
     */
    private array $mappings = [
        'name'          => 'name',
        'vat_number'    => 'vat_number',
        'terms'         => 'terms',
        'public notes'  => 'public_notes',
        'private notes' => 'private_notes',
        'contacts'      => [
            'first_name' => 'first_name',
            'last_name'  => 'last_name',
            'email'      => 'email',
            'phone'      => 'phone'
        ]
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
                'first_name' => ['required', 'cast' => 'string'],
                'last_name'  => ['cast' => 'string'],
                'email'      => ['cast' => 'string'],
                'phone'      => ['cast' => 'string'],
                'name'       => ['cast' => 'string'],
                'vat_number' => ['required', 'cast' => 'string'],
                //'due date'      => ['cast' => 'date'],
                //'customer_id' => ['required', 'cast' => 'int'],
            ],
            'config'    => [
                'csv_date_format' => 'Y-m-d'
            ]
        ];
    }

    /**
     * @param array $params
     * @return Customer
     */
    public function factory(array $params): ?Customer
    {
        return CustomerFactory::create($this->account, $this->user);
    }

    /**
     * @return CustomerRepository
     */
    public function repository(): CustomerRepository
    {
        return new CustomerRepository(new Customer(), new CustomerContactRepository(new CustomerContact()));
    }

    public function transformObject($object)
    {
        return $this->transformCustomer($object);
    }

    /**
     * @param Customer $customer
     * @param array $data
     */
    public function saveCallback(Customer $customer, array $data)
    {
        if (!empty($data['contacts'])) {
            (new CustomerContactRepository(new CustomerContact()))->save($data['contacts'], $customer);
        }

        return $customer->fresh();
    }

    public function customHandler()
    {
    }
}
