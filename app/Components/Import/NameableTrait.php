<?php


namespace App\Components\Import;


use ReflectionClass;
use ReflectionException;

trait NameableTrait
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     * @throws ReflectionException
     */
    public function __toString()
    {
        return ($this->name && is_string($this->name)) ? $this->name : (new ReflectionClass($this))->getShortName();
    }
}