<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orderList = \DB::table('mshop_order_base')
                ->join('mshop_order', 'mshop_order_base.id', '=', 'mshop_order.baseid')
                ->join('users', 'users.id', '=', 'mshop_order_base.customerid')
                ->select(
                    'mshop_order_base.id as order_id', 'mshop_order_base.currencyid','mshop_order_base.price','mshop_order_base.rebate','mshop_order_base.tax','mshop_order_base.ctime',
                    'users.id as user_id', 'users.name', 'users.email',
                    'mshop_order.statuspayment', 'mshop_order.statusdelivery'
                    )
                ->where('mshop_order.statuspayment', '>', -1)
                // ->where('mshop_order.statusdelivery', '>', -1)
                // ->get(6);
                ->paginate(6);

                // dd($orderList);

        return view('orders.index', compact('orderList'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $order_detail = \DB::table('mshop_order_base')
                ->join('mshop_order', 'mshop_order_base.id', '=', 'mshop_order.baseid')
                ->join('mshop_order_base_product', 'mshop_order_base.id', '=', 'mshop_order_base_product.baseid')
                ->join('users', 'users.id', '=', 'mshop_order_base.customerid')
                ->select(
                    'mshop_order_base.id as order_id', 'mshop_order_base.currencyid','mshop_order_base.price','mshop_order_base.rebate','mshop_order_base.tax',
                    'users.id as user_id', 'users.name', 'users.email',
                    'users.id as address1', 'users.city', 'users.state', 'users.countryid',
                    'mshop_order.statuspayment', 'mshop_order.statusdelivery',
                    'mshop_order_base_product.prodid', 
                    'mshop_order_base_product.prodcode', 
                    'mshop_order_base_product.name as product_name', 
                    'mshop_order_base_product.quantity as product_quantity', 
                    'mshop_order_base_product.mediaurl as product_mediaurl', 
                    'mshop_order_base_product.currencyid as product_currencyid', 
                    'mshop_order_base_product.price as product_price', 
                    'mshop_order_base_product.costs as product_costs',
                    'mshop_order_base_product.rebate as product_rebate',
                    'mshop_order_base_product.tax as product_tax',
                    )
                ->where('mshop_order_base.id', $id)->get();
        if($order_detail){
            return view('orders.show',compact('order_detail'));
        }else{
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = \DB::table('mshop_order_base')
            ->join('mshop_order', 'mshop_order_base.id', '=', 'mshop_order.baseid')
            ->join('users', 'users.id', '=', 'mshop_order_base.customerid')
            ->select(
                'mshop_order_base.id as order_id', 'mshop_order_base.currencyid','mshop_order_base.price','mshop_order_base.rebate','mshop_order_base.tax',
                'users.id as user_id', 'users.name', 'users.email',
                'mshop_order.statuspayment', 'mshop_order.statusdelivery'
                )
            ->where('mshop_order_base.id', $id)->first();
        return view('orders.edit',compact('order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'content' => 'required',
        ]);

        $input = $request->all();
        $blog->update($input);

        return redirect()->route('blogs.index')->with('success','Blog has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect()->route('blogs.index')->with('success','Blog has been deleted successfully');
    }
}