
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Shop\Attributes\Exceptions\AttributeNotFoundException;
use App\Shop\Attributes\Exceptions\CreateAttributeErrorException;
use App\Shop\Attributes\Exceptions\UpdateAttributeErrorException;
use App\Shop\Attributes\Repositories\AttributeRepository;
use App\Shop\Attributes\Repositories\AttributeRepositoryInterface;
use App\Shop\Attributes\Requests\CreateAttributeRequest;
use App\Shop\Attributes\Requests\UpdateAttributeRequest;

class AttributeController extends Controller
{
    private $attribute_repo;

    /**
     * AttributeController constructor.
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(AttributeRepository $attributeRepository)
    {
        $this->attribute_repo = $attributeRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $attributes = $this->attribute_repo->listAttributes();
        //$attributes = $this->attribute_repo->paginateArrayResults($results->all());

        return response()->json($attributes);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //return view('admin.attributes.create');
    }

    /**
     * @param CreateAttributeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateAttributeRequest $request)
    {
        $attribute = $this->attribute_repo->createAttribute($request->except('_token'));
        return response()->json($attribute);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
            $attribute = $this->attribute_repo->findAttributeById($id);
            $attributeRepo = new AttributeRepository($attribute);

            return response->json([
                'attribute' => $attribute,
                'values' => $attribute_repo->listAttributeValues()
            ]);
      
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $attribute = $this->attribute_repo->findAttributeById($id);

        return response->json($attribute);
    }

    /**
     * @param UpdateAttributeRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAttributeRequest $request, $id)
    {
        
            $attribute = $this->attribute_repo->findAttributeById($id);

            $attribute_repo = new AttributeRepository($attribute);
            $attribute_repo->updateAttribute($request->all());
            return response()->json($attribute);

    }

    /**
     * @param $id
     * @return bool|null
     */
    public function destroy($id)
    {
        $this->attribute_repo->findAttributeById($id)->delete();

        return response->json('Attribute deleted successfully!');
    }
}
