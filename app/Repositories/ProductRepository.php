<?php

namespace App\Repositories;

use App\Account;
use App\AttributeValue;
use App\Filters\ProductFilter;
use App\ProductListingHistory;
use App\Repositories\Base\BaseRepository;
use App\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Requests\SearchRequest;
use Exception;
use Illuminate\Support\Collection as Support;
use Illuminate\Database\Eloquent\Collection;
use App\Task;
use App\Category;
use App\ProductImage;
use App\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Traits\UploadableTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{

    use UploadableTrait;

    /**
     * ProductRepository constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        parent::__construct($product);
        $this->model = $product;
    }

    /**
     * @param SearchRequest $search_request
     * @param Account $account
     * @return \Illuminate\Pagination\LengthAwarePaginator|mixed
     */
    public function getAll(SearchRequest $search_request, Account $account)
    {
        return (new ProductFilter($this))->filter($search_request, $account);
    }

    /**
     * @param int $id
     * @return Product
     */
    public function findProductById(int $id): Product
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Delete the product
     *
     * @param Product $product
     *
     * @return bool
     * @throws Exception
     */
    public function deleteProduct(): bool
    {
        return $this->delete();
    }

    /**
     * Get the product via slug
     * @param string $slug
     * @return Product
     */
    public function findProductBySlug(string $slug): Product
    {
        return Product::where('slug', '=', $slug)->firstOrFail();
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * Detach the categories
     */
    public function detachCategories(Product $product)
    {
        $product->categories()->detach();
    }

    /**
     * Return the categories which the product is associated with
     *
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return $this->model->categories()->get();
    }

    /**
     * Sync the categories
     *
     * @param array $params
     */
    public function syncCategories(array $params, Product $product)
    {
        $product->categories()->sync($params);
    }

    /**
     * @return Brand
     */
    public function findBrand()
    {
        return $this->model->brand;
    }

    /**
     *
     * @param Brand $objBrand
     * @return Support
     */
    public function filterProductsByBrand(Brand $objBrand): Support
    {
        return $this->model->where('company_id', $objBrand->id)->get();
    }

    /**
     *
     * @param Category $objCategory
     * @return Support
     */
    public function filterProductsByCategory(Category $objCategory): Support
    {
        return $this->model->join('category_product', 'category_product.product_id', '=', 'products.id')
                           ->select('products.*')->where('category_product.category_id', $objCategory->id)
                           ->groupBy('products.id')->get();
    }

    /**
     * Delete the attribute from the product
     *
     * @param ProductAttribute $productAttribute
     *
     * @return bool|null
     * @throws Exception
     */
    public function removeProductAttribute(ProductAttribute $productAttribute, Product $product): ?bool
    {
        return $product->attributes()->delete();
    }

    /**
     * List all the product attributes associated with the product
     *
     * @return Collection
     */
    public function listProductAttributes(): Collection
    {
        return $this->model->attributes()->get();
    }

    /**
     * Associate the product attribute to the product
     *
     * @param ProductAttribute $productAttribute
     * @return ProductAttribute
     */
    public function saveProductAttributes(ProductAttribute $productAttribute, Product $product): ProductAttribute
    {
        $this->model->attributes()->save($productAttribute);
        return $productAttribute;
    }

    /**
     * @return Collection
     */
    public function listCombinations(): Collection
    {
        return $this->model->attributes()->map(
            function (ProductAttribute $productAttribute) {
                return $productAttribute->attributesValues;
            }
        );
    }

    /**
     * @param ProductAttribute $productAttribute
     * @param AttributeValue ...$attributeValues
     *
     * @return Collection
     */
    public function saveCombination(
        ProductAttribute $productAttribute,
        AttributeValue ...$attributeValues
    ): \Illuminate\Support\Collection {
        return collect($attributeValues)->each(
            function (AttributeValue $value) use ($productAttribute) {
                return $productAttribute->attributesValues()->save($value);
            }
        );
    }

    /**
     * @param ProductAttribute $productAttribute
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findProductCombination(ProductAttribute $productAttribute)
    {
        $values = $productAttribute->attributesValues()->get();

        return $values->map(
            function (AttributeValue $attributeValue) {
                return $attributeValue;
            }
        )->keyBy(
            function (AttributeValue $item) {
                return strtolower($item->attribute->name);
            }
        )->transform(
            function (AttributeValue $value) {
                return $value->value;
            }
        );
    }

    /**
     *
     * @param Category $category
     * @param type $value
     */
    public function getProductsByDealValueAndCategory(Category $category, Request $request): Support
    {
        $query = $this->model->leftJoin('product_attributes', 'product_attributes.product_id', '=', 'products.id')
                             ->join('category_product', 'category_product.product_id', '=', 'products.id')
                             ->select('products.*')
                             ->where('products.status', '=', 1)->where(
                'category_product.category_id',
                '=',
                $category->id
            );

        if (!empty($request->valued_at) && $request->valued_at > 0) {
            $query->where('product_attributes.range_from', '<', $request->valued_at)
                  ->where('product_attributes.range_to', '>', $request->valued_at);
        }

        return $query->get();
    }

    /**
     * @param $file
     * @param null $disk
     * @return bool
     */
    public function deleteFile(array $file, $disk = null): bool
    {
        return $this->update(['cover' => null], $file['product']);
    }

    /**
     * @param string $src
     * @return bool
     */
    public function deleteThumb(string $src): bool
    {
        return DB::table('product_images')->where('src', $src)->delete();
    }

    /**
     * @return mixed
     */
    public function findProductImages(Product $product): Collection
    {
        return $product->images()->get();
    }

    private function addListingHistory(array $data, Product $product)
    {
        $relevant_fields_for_history = [
            'price',
            'cost'
        ];

        $changed_fields = array_intersect_key($data, array_flip($relevant_fields_for_history));
        $saved_fields = array_intersect_key($product->toArray(), array_flip($relevant_fields_for_history));

        $diff = array_diff($changed_fields, $saved_fields);

        if (!empty($diff)) {
            ProductListingHistory::create(
                [
                    'product_id' => $product->id,
                    'changes'    => $diff,
                    'user_id'    => $product->user_id,
                    'account_id' => $product->account_id
                ]
            );
        }
    }

    /**
     * @param $data
     * @param Product $product
     * @return Product|null
     */
    public function save(array $data, Product $product): ?Product
    {
        if (!empty($product->id)) {
            $this->addListingHistory($data, $product);
        }

        $product->fill($data);
        $product->save();

        return $product;
    }


}
