<?php

namespace QuantumCA\Sdk\Resources;

class Product extends AbstractResource
{
    /**
     * 列出产品及价格
     *
     * @return \QuantumCA\Sdk\Scheme\ProductListScheme
     */
    public function productList()
    {
        return $this->client->get('product/list');
    }
}