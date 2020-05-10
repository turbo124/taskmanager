<?php

namespace App\Repositories;

use App\Attribute;
use App\AttributeValue;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;

class AttributeRepository extends BaseRepository
{
    /**
     * @var Attribute
     */
    protected $model;

    /**
     * AttributeRepository constructor.
     * @param Attribute $attribute
     */
    public function __construct(Attribute $attribute)
    {
        parent::__construct($attribute);
        $this->model = $attribute;
    }

    /**
     * @param array $data
     * @return Attribute
     * @throws CreateAttributeErrorException
     */
    public function createAttribute(array $data): Attribute
    {
        $attribute = new Attribute($data);
        $attribute->save();
        return $attribute;
    }

    /**
     * @param int $id
     * @return Attribute
     * @throws AttributeNotFoundException
     */
    public function findAttributeById(int $id): Attribute
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @param array $data
     * @return bool
     * @throws UpdateAttributeErrorException
     */
    public function updateAttribute(array $data): bool
    {
        return $this->model->update($data);

    }

    /**
     * @return bool|null
     */
    public function deleteAttribute(): ?bool
    {
        return $this->model->delete();
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return Collection
     */
    public function listAttributes($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc'): Collection
    {
        return $this->all($columns, $orderBy, $sortBy);
    }

    /**
     * @return Collection
     */
    public function listAttributeValues(): Collection
    {
        return $this->model->values()->get();
    }

    /**
     * @param AttributeValue $attributeValue
     * @return AttributeValue
     */
    public function associateAttributeValue(AttributeValue $attributeValue): AttributeValue
    {
        return $this->model->values()->save($attributeValue);
    }
}
