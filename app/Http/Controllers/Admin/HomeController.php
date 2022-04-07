<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\User\AppUser;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $appUsers = 0;
        $agents = 0;
        $approvedOrders = 0;
        $canceledOrders = 0;
        $agentsOrders = [];
        if (auth()->user()->hasRole('admin')) {
            $appUsers = AppUser::count();
            $orders = Order::count();
            $agents = User::count();
            $agentsOrders = User::get();
            $todayIncome = Order::wheredate('created_at', Carbon::today())->where('status', 2)->get()->sum('total_amount_with_fees');
            $monthIncome = Order::wheredate('created_at', '>=', Carbon::today()->subMonth())->where('status', 2)->get()->sum('total_amount_with_fees');
            $yearIncome = Order::wheredate('created_at', '>=', Carbon::today()->subYear())->where('status', 2)->get()->sum('total_amount_with_fees');
        }else{
            $orders = Order::where('user_id',auth()->user()->id)->count();
            $approvedOrders = Order::where('user_id',auth()->user()->id)->where('status',2)->count();
            $canceledOrders = Order::where('user_id',auth()->user()->id)->where('status',3)->count();
            $todayIncome = Order::where('user_id',auth()->user()->id)->wheredate('created_at', Carbon::today())->where('status', 2)->get()->sum('total_amount_with_fees');
            $monthIncome = Order::where('user_id',auth()->user()->id)->wheredate('created_at', '>=', Carbon::today()->subMonth())->where('status', 2)->get()->sum('total_amount_with_fees');
            $yearIncome = Order::where('user_id',auth()->user()->id)->wheredate('created_at', '>=', Carbon::today()->subYear())->where('status', 2)->get()->sum('total_amount_with_fees');
        }
        return view('admin.main.home',compact('appUsers','agentsOrders','orders','approvedOrders','canceledOrders','agents','todayIncome','monthIncome','yearIncome'));
    }

    public function telescope(){
        return view('admin.main.telescope');

    }

    public function logout(){
        Auth::logout();
        return redirect('login/');
    }
}
