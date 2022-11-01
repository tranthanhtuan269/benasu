<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use Aimeos\Shop\Facades\Shop;
use App\Models\User;

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

    public function indexAction()
    {
        foreach( config( 'shop.page.favorite' ) as $name )
        {
            $params['aiheader'][$name] = Shop::get( $name )->header();
            $params['aibody'][$name] = Shop::get( $name )->body();
        }
        // do some more stuff
        return \View::make('account.favorite', $params);
    }

    public function referAction(Request $request)
    {
        if($request->prefer_code){
            // find user refer
            $refer_id = substr($request->prefer_code, 8);
            $refer = User::find($refer_id);
            if($refer){
                $user = \Auth::user();
                $user->refer_id = $refer_id;
                $user->save();

                return response()->json(['status'=>'200', 'message' => 'Save Success']);
            }else{
                return response()->json(['status'=>'404', 'message' => 'Not found']);
            }
        }else{
            return response()->json(['status'=>'301', 'message' => 'Not success']);
        }
    }
}
