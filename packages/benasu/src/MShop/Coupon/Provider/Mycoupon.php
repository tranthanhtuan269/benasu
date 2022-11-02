<?php

namespace Aimeos\MShop\Coupon\Provider;

class Mycoupon
    extends \Aimeos\MShop\Coupon\Provider\Factory\Base
    implements Iface, \Aimeos\MShop\Coupon\Provider\Factory\Iface
{
    public function update( \Aimeos\MShop\Order\Item\Base\Iface $base ) : \Aimeos\MShop\Coupon\Provider\Iface
    {
        $user = \Auth::user();
        $coins = $user->coins;
        $products = $this->createRebateProducts( $base, 'demo-rebate', $coins );
        $base->setCoupon( $this->getCode(), $products );
        return $this;
    }
}