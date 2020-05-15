
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Shop\Attributes\Repositories\AttributeRepositoryInterface;
use App\Shop\AttributeValues\AttributeValue;
use App\Shop\AttributeValues\Repositories\AttributeValueRepository;
use App\Shop\AttributeValues\Repositories\AttributeValueRepositoryInterface;
use App\Shop\AttributeValues\Requests\CreateAttributeValueRequest;

class AttributeValueController extends Controller
{
    /**
     * @var AttributeRepositoryInterface
     */
    private $attribute_repo;

    /**
     * @var AttributeValueRepositoryInterface
     */
    private $attribute_value_repo;

    /**
     * AttributeValueController constructor.
     * @param AttributeRepositoryInterface $attributeRepository
     * @param AttributeValueRepositoryInterface $attributeValueRepository
     */
    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeValueRepositoryInterface $attributeValueRepository
    ) {
        $this->attribute_repo = $attributeRepository;
        $this->attribute_value_repo = $attributeValueRepository;
    }

    public function create($id)
    {
        return response()->json([
            'attribute' => $this->attribute_repo->findAttributeById($id)
        ]);
    }

    /**
     * @param CreateAttributeValueRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateAttributeValueRequest $request, $id)
    {
        $attribute = $this->attribute_repo->findAttributeById($id);

        $attribute_value = new AttributeValue($request->all());
        $attribute_value_repo = new AttributeValueRepository($attribute_value);

        $attribute_value_repo->associateToAttribute($attribute);

        return response()->json($attribute_value);
    }

    /**
     * @param $attributeId
     * @param $attributeValueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($attribute_id, $attribute_value_id)
    {
        $attribute_value = $this->attribute_value_repo->findOneOrFail($attribute_value_id);

        $attribute_value_repo = new AttributeValueRepository($attribute_value);
        $attribute_value_repo->dissociateFromAttribute();

       return response()->json($attribute_value);
    }
}
