<?php
namespace App\Traits;

use App\Requests\SearchRequest;

trait EntityDataBuilder
{
    private array $errors = [];

    private string $entity_string = '';

    private $repository;

    private $entity_class;

    private $filter_class;

    public function setEntity($entity_class)
    {
        $this->entity_string = (new \ReflectionClass($entity_class))->getShortName();
        $this->entity_class = $entity_class;
    }

    public function setRepositoryClass()
    {
        $repo = 'App\Repositories\\'.$this->entity_string.'Repository';

         if(!class_exists($repo)) {
            $this->errors[] = 'Unable to find repo';
        }

        $this->repository = new $repo($this->entity);

        return true;
     }

    public function setFilterClass()
    {
        $filter_class = 'App\Filters\\'.$this->entity_string.'Filter';

        if(!class_exists($filter_class)) {
            $this->errors[] = 'Unable to find filter class';
        }

        $this->filter_class = new $filter_class($this->repository);
    }

    public function buildEntityData($entity)
    {
         $this->setEntity($entity);
         $this->setRepositoryClass();
         $this->setFilterClass();

        if(count($this->errors) > 0) {
            return false;
        }

         if(!method_exists($this->filter_class, 'filter')) {
            $this->errors[] = "Unable to filter";
         }

        $data = $this->filter_class->filter(new SearchRequest(), $this->entity->account_id);

        if(empty($data)) {
             return false;
        }

        $formatted_data = collect($data)->keyBy('id');
        $entity_data = $formatted_data[$entity->id];

        if(empty($entity_data)) {
            return false;
        }

        return $entity_data;
        
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
