<?php


namespace App\Components\Import;


use App\Components\Product\CreateProduct;
use App\Factory\BrandFactory;
use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use App\Models\Account;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Transformations\ProductTransformable;

class ProductImporter extends BaseCsvImporter
{
    use ImportMapper;
    use ProductTransformable;

    /**
     * @var array|string[]
     */
    private array $mappings = [
        'name'          => 'name',
        'description'   => 'description',
        'price'         => 'price',
        'cost'          => 'cost',
        'length'        => 'length',
        'width'         => 'width',
        'height'        => 'height',
        'weight'        => 'weight',
        'quantity'      => 'quantity',
        'sku'           => 'sku',
        'category name' => 'category_name',
        'brand name'    => 'brand_id',
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
            'mappings' => [
                'name'          => ['required', 'cast' => 'string'],
                'sku'           => ['cast' => 'string'],
                'description'   => ['required', 'cast' => 'string'],
                'price'         => ['cast' => 'float'],
                'cost'          => ['cast' => 'float'],
                'category name' => ['cast' => 'string'],
                'brand name'    => ['cast' => 'string'],
                'quantity'      => ['required', 'cast' => 'int'],
                'width'         => ['cast' => 'float'],
                'height'        => ['cast' => 'float'],
                'weight'        => ['cast' => 'float'],
                'length'        => ['cast' => 'float'],
            ],
            'config'   => [
                'csv_date_format' => 'Y-m-d'
            ]
        ];
    }

    /**
     * @param array $params
     * @return Product
     */
    public function factory(array $params): ?Product
    {
        return ProductFactory::create($this->user, $this->account);
    }

    /**
     * @return ProductRepository
     */
    public function repository(): ProductRepository
    {
        return new ProductRepository(new Product());
    }

    public function transformObject($object)
    {
        return $this->transformProduct($object);
    }

    /**
     * @param Customer $customer
     * @param array $data
     */
    public function saveCallback(Product $product, array $data)
    {
        if (empty($data['category_name'])) {
            return $product;
        }

        $category_id = $this->getCategory($data['category_name']);
        $data['category'] = $category_id;

        $product = (new CreateProduct($this->repository(), $data, $product))->execute();

        return $product->fresh();
    }

    /**
     * @param string $value
     * @return int
     */
    public function getCategory(string $value)
    {
        if (empty($this->categories)) {
            $this->categories = Category::where('account_id', $this->account->id)->where(
                'is_deleted',
                false
            )->get()->keyBy('name')->toArray();

            $this->categories = array_change_key_case($this->categories, CASE_LOWER);
        }

        if (empty($this->categories)) {
            return null;
        }

        if (empty($this->categories[strtolower($value)])) {
            $category = (new CategoryFactory())->create($this->account, $this->user);
            $category = (new CategoryRepository(new Category()))->createCategory(
                ['name' => $value],
                $category
            );
            return $category->id;
        }

        $category = $this->categories[strtolower($value)];

        return $category['id'];
    }

    /**
     * @param string $value
     * @return int
     */
    private function getBrand(string $value): ?int
    {
        if (empty($this->brands)) {
            $this->brands = Brand::where('account_id', $this->account->id)->where(
                'is_deleted',
                false
            )->get()->keyBy('name')->toArray();

            $this->brands = array_change_key_case($this->brands, CASE_LOWER);
        }

        if (empty($this->brands)) {
            return null;
        }

        if (empty($this->brands[strtolower($value)])) {
            $brand = (new BrandFactory())->create($this->account, $this->user);
            $brand = (new BrandRepository(new Brand()))->save(
                ['name' => $value],
                $brand
            );
            return $brand->id;
        }

        $brand = $this->brands[strtolower($value)];

        return $brand['id'];
    }
}
