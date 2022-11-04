<?php

namespace Aimeos\MShop\Rule\Provider\Catalog;

class Myprovider
    extends \Aimeos\MShop\Rule\Provider\Base
    implements \Aimeos\MShop\Rule\Provider\Catalog\Iface, \Aimeos\MShop\Rule\Provider\Factory\Iface
{
    public function apply( \Aimeos\MShop\Product\Item\Iface $product ) : bool
    {
        return $this->isLast();
    }
}