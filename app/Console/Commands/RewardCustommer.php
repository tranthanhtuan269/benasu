<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class RewardCustommer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:rewardCustommer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto reward for Custommer';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $order_list = \DB::table('mshop_order_base')
                ->join('mshop_order', 'mshop_order_base.id', '=', 'mshop_order.baseid')
                ->join('users', 'users.id', '=', 'mshop_order_base.customerid')
                ->select(
                    'mshop_order_base.id as order_id', 'mshop_order_base.currencyid','mshop_order_base.price','mshop_order_base.rebate','mshop_order_base.tax',
                    'users.id as user_id', 'users.name', 'users.email', 'users.coins',
                    'users.id as address1', 'users.city', 'users.state', 'users.countryid',
                    'mshop_order.statuspayment', 'mshop_order.statusdelivery'
                    )
                ->where('mshop_order.statuspayment', '>', -1)
                ->where('mshop_order.statusdelivery', '>', -1)
                ->where('mshop_order.statusreward', -1)->get();

        foreach($order_list as $order_detail){
            if($order_detail){
                // - First time sign up will have 3% off in total order it will be forever.
                $user_id = $order_detail->user_id;

                $user = User::find($user_id);

                $check_first_time = \DB::table('mshop_order_base')
                                ->join('mshop_order', 'mshop_order_base.id', '=', 'mshop_order.baseid')
                                ->where('mshop_order.statusreward', 1)
                                ->where('mshop_order_base.customerid', $user_id)->count();

                if($user){
                    if($check_first_time == 0){
                        if(!isset($user->coins)){
                            $user->coins = 0 + floatval($order_detail->price * 0.03);
                        }else{
                            $user->coins = $user->coins + floatval($order_detail->price * 0.03);
                        }
                        $user->save();
                    }

                    // - They will have a code to refer friends and get 3% credit in friend’s order, this money only use to buy something in the website
                    $refer_lv1 = $user->refer;

                    if($refer_lv1){
                        $number_refer_of_lv1 = count($refer_lv1->refers);
                        // - The system also needs to limit the number users can refer, maximum is 20 and other referrals will be down to 1% credit.
                        if($number_refer_of_lv1 > 20){
                            if(!isset($refer_lv1->coins)){
                                $refer_lv1->coins = 0 + floatval($order_detail->price * 0.01);
                            }else{
                                $refer_lv1->coins = $refer_lv1->coins + floatval($order_detail->price * 0.01);
                            }
                            $refer_lv1->save();
                            
                            $refer_lv2 = $refer_lv1->refer;
                            if($refer_lv2){
                                if(!isset($refer_lv2->coins)){
                                    $refer_lv2->coins = 0 + floatval($order_detail->price * 0.01);
                                }else{
                                    $refer_lv2->coins = $refer_lv2->coins + floatval($order_detail->price * 0.01);
                                }
                                $refer_lv2->save();
                            }
                        }else{
                            if(!isset($refer_lv1->coins)){
                                $refer_lv1->coins = 0 + floatval($order_detail->price * 0.03);
                            }else{
                                $refer_lv1->coins = $refer_lv1->coins + floatval($order_detail->price * 0.03);
                            }
                            $refer_lv1->save();
                            
                            $refer_lv2 = $refer_lv1->refer;
                            if($refer_lv2){
                                if(!isset($refer_lv2->coins)){
                                    $refer_lv2->coins = 0 + floatval($order_detail->price * 0.01);
                                }else{
                                    $refer_lv2->coins = $refer_lv2->coins + floatval($order_detail->price * 0.01);
                                }
                                $refer_lv2->save();
                            }
                        }
                    }
                }

                \DB::table('mshop_order')
                    ->where('id', $order_detail->order_id)
                    ->update(['statusreward' => 1]);
            }
        }
        return Command::SUCCESS;
    }
}
