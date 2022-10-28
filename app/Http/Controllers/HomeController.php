<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;

class HomeController extends Controller
{
    public function test(){
        $orderList = \DB::table('mshop_order_base')
                ->join('mshop_order', 'mshop_order_base.id', '=', 'mshop_order.baseid')
                ->join('users', 'users.id', '=', 'mshop_order_base.customerid')
                ->select(
                    'mshop_order_base.id as order_id', 'mshop_order_base.currencyid','mshop_order_base.price','mshop_order_base.rebate','mshop_order_base.tax',
                    'users.id as user_id', 'users.name', 'users.email',
                    'mshop_order.statuspayment', 'mshop_order.statusdelivery'
                    )
                ->get();
        dd($orderList);

        return view('orders.index', compact('orderList'));

    }
}
