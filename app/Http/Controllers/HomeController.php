<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use Aimeos\Shop\Facades\Shop;
use App\Models\User;

class HomeController extends Controller
{
    public function test(){
        dd(md5(uniqid(rand(), true)));
        \DB::table('mshop_coupon_code')->insert([
            'siteid' => '1.',
            'parentid' => 5, 
            'count' => 1,
            'code' => md5(uniqid(rand(), true)),
            'mtime' => date("Y-m-d H:i:s"),
            'ctime' => date("Y-m-d H:i:s"),
            'editor' => 'admin@myshop.test'
        ]);
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

    public function createCoupon(Request $request)
    {
        if(\Auth::check()){
            $user = \Auth::user();

            if($user->coins < 1000){
                return response()->json(['status'=>'-1', 'message' => 'Your account is not enough!']);
            }

            $user->coins = $user->coins - 1000.0;
            $user->save();

            $coupon = md5(uniqid(rand(), true));

            \DB::table('mshop_coupon_code')->insert([
                'siteid' => '1.',
                'parentid' => 9, 
                'count' => 1,
                'code' => $coupon,
                'mtime' => date("Y-m-d H:i:s"),
                'ctime' => date("Y-m-d H:i:s"),
                'editor' => 'admin@myshop.test'
            ]);
            
            return response()->json(['status'=>'200', 'message' => 'Your redemption was successful', 'coupon' => $coupon]);
        }else{
            return response()->json(['status'=>'301', 'message' => 'Not success']);
        }
    }
}
