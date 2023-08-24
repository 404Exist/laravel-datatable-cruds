<?php

namespace Exist404\DatatableCruds\Exceptions;

class ModelIsNotSet extends \InvalidArgumentException
{
    public static function create()
    {
        return new static("You must set model by using `for()` method or set table by using `forTable()` method");
    }
}
