<?php

namespace Exist404\DatatableCruds\Exceptions;

class MethodNotAllowedWithCurrentInstance extends \InvalidArgumentException
{
    public static function create(string $instance, string $method)
    {
        return new static("You can't call the $method() method with creating $instance() method");
    }
}
