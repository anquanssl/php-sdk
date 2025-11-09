<?php

namespace QuantumCA\Sdk\Resources;

use QuantumCA\Sdk\Client;

abstract class AbstractResource
{
    /**
     * @var Client
     */
    protected $client = null;

    public function __construct($client)
    {
        $this->client = $client;
    }
}
