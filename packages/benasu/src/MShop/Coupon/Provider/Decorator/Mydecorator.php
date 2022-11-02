<?php

namespace Aimeos\MShop\Coupon\Provider\Decorator;

class Mydecorator
    extends \Aimeos\MShop\Coupon\Provider\Decorator\Base
    implements \Aimeos\MShop\Coupon\Provider\Decorator\Iface
{
    public function calcPrice( \Aimeos\MShop\Order\Item\Base\Iface $base ) : \Aimeos\MShop\Price\Item\Iface
    {
        // do something before
        $priceItem = $this->getProvider()->calcPrice( $base ) - 10;
        // do something after

        return $priceItem;
    }
}