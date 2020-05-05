<?php
namespace App\Traits;

trait EntityDataBuilder
{
    private $errors = [];

    public function setEntity()
    {

    }

    public function setRepositoryClass()
    {
         if(!class_exists($repo)) {
             $errors[] = 'Unable to find repo';
        }
     }

    public function setFilterClass()
    {
        if(!class_exists($filter_class)) {
             $errors[] = 'Unable to find filter class';
        }
    }

    public function buildEntityData()
    {
         $this->setEntity();
         $this->setRepositoryClass();
         $this->setFilterClass();

         /*if(!method_exists()) {

         }^/

        if(count($this->errors) > 0) {
            return false;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
