<?php


namespace App\Components\Import;


use App\Factory\ExpenseFactory;
use App\Models\Account;
use App\Models\Expense;
use App\Models\User;
use App\Repositories\ExpenseRepository;
use App\Transformations\ExpenseTransformable;

class DealImporter extends BaseCsvImporter
{
    use ImportMapper;
    use ExpenseTransformable;

    /**
     * @var array|string[]
     */
    private array $mappings = [
        'category name' => 'expense_category',
        'description'   => 'description',
        'amount'        => 'amount',
        'currency code' => 'currency_id',
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
                'category_name'        => ['required', 'cast' => 'string'],
                'description' => ['cast' => 'string'],
                'amount'      => ['cast' => 'float'],
                'due_date'    => ['cast' => 'date'],
                'currency_id' => ['required', 'cast' => 'int'],
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
     * @return Expense
     */
    public function factory(array $params): ?Expense
    {
        return ExpenseFactory::create($this->user, $this->account);
    }

    /**
     * @return ExpenseRepository
     */
    public function repository(): ExpenseRepository
    {
        return new ExpenseRepository(new Expense());
    }

    public function transformObject($object)
    {
        return $this->transformExpense($object);
    }

    public function customHandler()
    {
    }
}
