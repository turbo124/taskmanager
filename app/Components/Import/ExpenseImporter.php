<?php


namespace App\Components\Import;


use App\Factory\ExpenseCategoryFactory;
use App\Factory\ExpenseFactory;
use App\Models\Account;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use App\Repositories\ExpenseCategoryRepository;
use App\Repositories\ExpenseRepository;
use App\Transformations\ExpenseTransformable;

class ExpenseImporter extends BaseCsvImporter
{
    use ImportMapper;
    use ExpenseTransformable;

    /**
     * @var array|string[]
     */
    private array $mappings = [
        'expense category name' => 'expense_category_id',
        'company name'          => 'company_id',
        'customer name'         => 'customer_id',
        'payment type'          => 'payment_type_id',
        'transaction reference' => 'transaction_reference',
        'project name'          => 'project_id',
        'date'                  => 'date',
        'amount'                => 'amount',
        'currency code'         => 'currency_id',
        'terms'                 => 'terms',
        'public notes'          => 'public_notes',
        'private notes'         => 'private_notes'
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
                'expense category name' => ['required', 'cast' => 'string'],
                'company name'          => ['cast' => 'string'],
                'customer name'         => ['cast' => 'string'],
                'payment type'          => ['cast' => 'string'],
                'transaction_reference' => ['cast' => 'string'],
                'project name'          => ['cast' => 'string'],
                'amount'                => ['cast' => 'float'],
                'date'                  => ['cast' => 'date'],
                'currency code'         => ['required', 'cast' => 'string'],
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

    public function getExpenseCategory(string $value)
    {
        if (empty($this->expense_categories)) {
            $this->expense_categories = ExpenseCategory::where('account_id', $this->account->id)->where(
                'is_deleted',
                false
            )->get()->keyBy('name')->toArray();

            $this->expense_categories = array_change_key_case($this->expense_categories, CASE_LOWER);
        }

        if (empty($this->expense_categories)) {
            return null;
        }

        if (empty($this->expense_categories[strtolower($value)])) {
            $expense_category = (new ExpenseCategoryFactory())->create($this->account, $this->user);
            $expense_category = (new ExpenseCategoryRepository(new ExpenseCategory()))->save(
                ['name' => $value],
                $expense_category
            );
            return $expense_category->id;
        }

        $expense_category = $this->expense_categories[strtolower($value)];

        return $expense_category['id'];
    }
}
