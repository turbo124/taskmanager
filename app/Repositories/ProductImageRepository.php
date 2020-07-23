<?php

namespace App\Repositories;

use App\Repositories\Base\BaseRepository;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageRepository extends BaseRepository
{
    /**
     * ProductImageRepository constructor.
     * @param ProductImage $productImage
     */
    public function __construct(ProductImage $productImage)
    {
        parent::__construct($productImage);
        $this->model = $productImage;
    }

    /**
     * @return mixed
     */
    public function findProduct(): Product
    {
        return $this->model->product;
    }
}
