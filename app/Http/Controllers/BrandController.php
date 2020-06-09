<?php

namespace App\Http\Controllers;

use App\Factory\BrandFactory;
use App\Repositories\BrandRepository;
use App\Requests\Brand\CreateBrandRequest;
use App\Requests\Brand\UpdateBrandRequest;

/**
 * Class BrandController
 * @package App\Http\Controllers
 */
class BrandController extends Controller
{
    /**
     * @var BrandRepository
     */
    private BrandRepository $brand_repo;

    /**
     * BrandController constructor.
     * @param BrandRepository $brandRepository
     */
    public function __construct(BrandRepository $brand_repo)
    {
        $this->brand_repo = $brand_repo;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = $this->brand_repo->paginateArrayResults($this->brand_repo->listBrands(['*'], 'name', 'asc')->all());

        return response()->json($data);
    }

    /**
     * @param CreateBrandRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateBrandRequest $request)
    {
        $brand = $this->brand_repo->save(
            $request->all(),
            BrandFactory::create(auth()->user()->account_user()->account, auth()->user())
        );
        return response()->json($brand);
    }

    /**
     * @param UpdateBrandRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBrandRequest $request, $id)
    {
        $brand = $this->brand_repo->findBrandById($id);

        $brandRepo = new BrandRepository($brand);
        $brand = $brandRepo->save($request->all(), $brand);

        return response()->json($brand);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $brand = $this->brand_repo->findBrandById($id);

        $brandRepo = new BrandRepository($brand);
        $brandRepo->dissociateProducts();
        $brandRepo->deleteBrand();

        return response()->json('deleted');
    }
}