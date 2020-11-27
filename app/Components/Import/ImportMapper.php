<?php


namespace App\Components\Import;


use App\Factory\CompanyFactory;
use App\Models\Company;
use App\Models\CompanyContact;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Project;
use App\Repositories\CompanyContactRepository;
use App\Repositories\CompanyRepository;

trait ImportMapper
{
    /**
     * @var array
     */
    private array $customers = [];

    /**
     * @var array
     */
    private array $companies = [];

    /**
     * @var array
     */
    private array $currencies = [];

    /**
     * @var array
     */
    private array $products = [];

    /**
     * @var array
     */
    private array $projects = [];

    /**
     * @var array
     */
    private array $payment_types = [];

    /**
     * @var array|string[]
     */
    private array $converters = [
        'product'               => 'getProduct',
        'customer name'         => 'getCustomer',
        'brand name'            => 'getBrand',
        'expense category name' => 'getExpenseCategory',
        'company name'          => 'getCompany',
        'payment type'          => 'getPaymentType',
        'project name'          => 'getProject',
        'currency code'           => 'getCurrency'
    ];

    private array $success = [];

    public function after()
    {
        //TODO
    }

    /**
     * Will be executed for a csv line if it passed validation
     * @param $items
     * @return bool
     */
    public function handle($items)
    {

        $object = $this->buildObject($items);

        $factory = $this->factory($object);

        if (!$factory) {
            return false;
        }

        $repo = $this->repository();

        $result = $repo->save($object, $factory);

        if (method_exists($this, 'saveCallback')) {
            $result = $this->saveCallback($result, $object);
        }

        $this->success[] = $this->transformObject($result);
    }

    private function buildObject($items)
    {
        $object = [];
        $count = 0;

        foreach ($this->mappings as $key => $columns) {
            if (is_array($columns)) {
                foreach ($columns as $column => $field) {
                    if (!isset($items[$column])) {
                        continue;
                    }

                    if (isset($this->converters[$column])) {
                        $value = $this->{$this->converters[$column]}(strtolower(trim($items[$column])));

                        $object[$key][$count][$field] = $value;

                        continue;
                    }

                    $object[$key][$count][$field] = $items[$column];
                }

                continue;
            }

            if (!isset($items[$key])) {
                continue;
            }

            if (isset($this->converters[$key])) {

                $value = $this->{$this->converters[$key]}(strtolower(trim($items[$key])));

                $object[$columns] = $value;

                continue;
            }

            $object[$columns] = $items[$key];

            $count++;
        }

        return $object;
    }


    /**
     *  Will be executed if a csv line did not pass validation
     *
     * @param $item
     * @return void
     */
    public function invalid($item)
    {
        $this->insertTo('invalid_entities', $item);
    }

    /**
     * @return array
     */
    public function getSuccess(): array
    {
        return $this->success;
    }

    /**
     * @param $value
     * @return int|null
     */
    private function getProduct(string $value): ?int
    {
        if (empty($this->products)) {
            $this->products = Product::where('account_id', $this->account->id)->where('is_deleted', false)->get(
            )->keyBy('name')->toArray();
            $this->products = array_change_key_case($this->products, CASE_LOWER);
        }

        if (empty($this->products) || empty($this->products[$value])) {
            return null;
        }

        $product = $this->products[$value];

        return $product['id'];
    }

    /**
     * @param $value
     * @return int|null
     */
    private function getCustomer(string $value): ?int
    {
        if (empty($this->customers)) {
            $customers = Customer::where('account_id', $this->account->id)->where('is_deleted', false)->get(
            )->keyBy('name');

            $this->customers = array_change_key_case($customers->toArray(), CASE_LOWER);
        }

        if (empty($this->customers) || empty($this->customers[strtolower($value)])) {
            return null;
        }

        $customer = $this->customers[strtolower($value)];
        $this->customer = $customers->where('id', $customer['id'])->first();

        return $this->customer->id;
    }

    private function getCompany(string $value)
    {

        if (empty($this->companies)) {
            $this->companies = Company::where('account_id', $this->account->id)->where('is_deleted', false)->get(
            )->keyBy('name')->toArray();
            $this->companies = array_change_key_case($this->companies, CASE_LOWER);
        }

        if (empty($this->companies)) {
            return null;
        }

        if (empty($this->companies[strtolower($value)])) {
            $company = (new CompanyFactory())->create($this->user, $this->account);
            $company = (new CompanyRepository(new Company(), new CompanyContactRepository(new CompanyContact())))->save(
                ['name' => $value],
                $company
            );
            return $company->id;
        }

        $company = $this->companies[strtolower($value)];

        return $company['id'];
    }

    private function getPaymentType(string $value)
    {
        if (empty($this->payment_types)) {
            $this->payment_types = PaymentMethod::all()->keyBy('name')->toArray();
            $this->payment_types = array_change_key_case($this->payment_types, CASE_LOWER);
        }

        if (empty($this->payment_types) || empty($this->payment_types[strtolower($value)])) {
            return null;
        }

        $payment_type = $this->payment_types[strtolower($value)];

        return $payment_type['id'];
    }

    private function getProject(string $value)
    {
        if (empty($this->projects)) {
            $this->projects = Project::where('account_id', $this->account->id)->where('is_deleted', false)->get()->keyBy('name')->toArray();
            $this->projects = array_change_key_case($this->projects, CASE_LOWER);
        }

        if (empty($this->projects) || empty($this->projects[strtolower($value)])) {
            return null;
        }

        $project = $this->projects[strtolower($value)];

        return $project['id'];
    }

    private function getCurrency(string $value)
    {

        if (empty($this->currencies)) {
            $this->currencies = Currency::all()->keyBy('iso_code')->toArray();
            $this->currencies = array_change_key_case($this->currencies, CASE_LOWER);
        }

        if (empty($this->currencies) || empty($this->currencies[strtolower($value)])) {
            return null;
        }

        $currency = $this->currencies[strtolower($value)];

        return $currency['id'];
    }
}
