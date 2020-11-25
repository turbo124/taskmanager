<?php


namespace App\Components\Import;


trait NameableTrait
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function __toString()
    {
        return ($this->name && is_string($this->name)) ? $this->name : (new \ReflectionClass($this))->getShortName();
    }
}