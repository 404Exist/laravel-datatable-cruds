<?php

namespace Exist404\DatatableCruds\Facades;

use Exist404\DatatableCruds\Builder\DatatableCruds as BuilderDatatableCruds;

class DatatableCruds
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
    public static function __callStatic($method, $arguments)
    {
        return (new BuilderDatatableCruds())->$method(...$arguments);
    }
    public function __call($method, $arguments)
    {
        return (new BuilderDatatableCruds())->$method(...$arguments);
    }
}
