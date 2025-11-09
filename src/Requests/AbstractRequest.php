<?php

namespace QuantumCA\Sdk\Requests;

use ArrayAccess;

abstract class AbstractRequest implements ArrayAccess
{
    public $service_id;
    public $show_all;
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }
    
    public function toArray()
    {
        return (array) $this;
    }
}
