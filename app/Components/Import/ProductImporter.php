<?php


namespace App\Components\Import;


use App\Factory\DealFactory;
use App\Models\Account;
use App\Models\Product;
use App\Models\User;
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
        'quantity'      => 'quantity',
        'category name' => 'category_name',
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
                'name'        => ['required', 'cast' => 'string'],
                'description' => ['required', 'cast' => 'string'],
                'price'       => ['cast' => 'float'],
                'category_name'    => ['cast' => 'string'],
                'quantity' => ['required', 'cast' => 'int'],
            ],
            'config'    => [
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

    public function customHandler()
    {
    }
}
