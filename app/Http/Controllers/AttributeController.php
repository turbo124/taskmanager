<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Filters\AttributeFilter;
use App\Http\Controllers\Controller;
use App\Jobs\Attribute\SaveAttributeValues;
use App\Repositories\AttributeRepository;
use App\Requests\Attribute\CreateAttributeRequest;
use App\Requests\Attribute\UpdateAttributeRequest;
use App\Requests\SearchRequest;
use App\Transformations\AttributeTransformable;

class AttributeController extends Controller
{
    private $attribute_repo;

    /**
     * AttributeController constructor.
     * @param AttributeRepository $attributeRepository
     */
    public function __construct(AttributeRepository $attributeRepository)
    {
        $this->attribute_repo = $attributeRepository;
    }

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(SearchRequest $request)
    {
        $attributes = (new AttributeFilter($this->attribute_repo))->filter(
            $request
        );
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateAttributeRequest $request)
    {
        $attribute = $this->attribute_repo->save(new Attribute(), $request->except('values'));
        SaveAttributeValues::dispatchNow($attribute, $request->input('values'));
        return response()->json((new AttributeTransformable)->transformAttribute($attribute));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $attribute = $this->attribute_repo->findAttributeById($id);
        $attribute_repo = new AttributeRepository($attribute);

        return response()->json(
            [
                'attribute' => $attribute,
                'values'    => $attribute_repo->listAttributeValues()
            ]
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $attribute = $this->attribute_repo->findAttributeById($id);

        return response()->json($attribute);
    }

    /**
     * @param UpdateAttributeRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAttributeRequest $request, $id)
    {
        $attribute = $this->attribute_repo->findAttributeById($id);

        $attribute_repo = new AttributeRepository($attribute);
        $attribute_repo->save($attribute, $request->all());
        SaveAttributeValues::dispatchNow($attribute, $request->input('values'));
        return response()->json((new AttributeTransformable)->transformAttribute($attribute));
    }

    /**
     * @param $id
     * @return bool|null
     */
    public function destroy($id)
    {
        $this->attribute_repo->findAttributeById($id)->delete();

        return response()->json('Attribute deleted successfully!');
    }
}
