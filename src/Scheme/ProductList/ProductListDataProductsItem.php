<?php

namespace QuantumCA\Sdk\Scheme\ProductList;

use QuantumCA\Sdk\Scheme\AbstractScheme;

/**
 * @property integer $id
 * @property string $name
 * @property bool $support_wildcard
 * @property bool $support_greenbar
 * @property bool $support_san
 * @property integer $validation_level
 * @property Object{"san_price":ProductListDataProductsItemPricing} $pricing
 */
class ProductListDataProductsItem extends AbstractScheme
{
}
