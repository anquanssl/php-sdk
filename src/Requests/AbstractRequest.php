<?php

namespace QuantumCA\Sdk\Requests;

use ArrayAccess;

#[\AllowDynamicProperties]
abstract class AbstractRequest implements ArrayAccess
{
    #[\ReturnTypeWillChange]
    public function offsetExists($offset): bool
    {
        return isset($this->{$offset});
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset): mixed
    {
        return $this->{$offset};
    }

    public function offsetSet($offset, $value): void
    {
        $this->{$offset} = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->{$offset});
    }
    
    #[\ReturnTypeWillChange]
    public function toArray(): array
    {
        return (array) $this;
    }
}
