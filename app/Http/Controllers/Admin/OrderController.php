<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Data\Setting;
use App\Models\Order\Cart;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Order\Transaction;
use App\Models\User\AppUser;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = [];
        $users = [];
        if (auth()->user()->hasRole('admin')) {
            $orders = Order::orderBy('id', 'desc')->paginate(15);
            $users = User::get();
        } else {
            $orders = Order::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->paginate(15);
        }
        return view('admin.orders.index', compact('orders', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function viewOrder()
    {
        $user_id = request()->get('user_id') ?? 0;
        $cart_id = request()->get('cart_id') ?? 0;
        if ($user_id == 0 or $cart_id == 0)
            return 'user id or cart id not valid';

        $cart = Cart::find($cart_id);
        $orderFees = Setting::where('key', 'order_fees')->first();
        $user = AppUser::find($user_id);
        return view('client.order', compact('cart', 'user', 'orderFees'));
    }

    public function callback()
    {
        $transaction = Transaction::insert(request()->input());
        $order_id = explode("-", request()->input('order_id'));
        $app_user_id = $order_id[2];
        $cart_id = $order_id[1];
        $cart = Cart::find($cart_id);
        if ($cart) {
            foreach ($cart->items as $cartItem) {
                $order = Order::where('user_id', $cartItem->product->agent_id)
                    ->where('app_user_id', $app_user_id)
                    ->where('cart_id', $cart_id)
                    ->where('transaction_id', request('transaction_id'))
                    ->first();
                if ($order) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->cart_item_id = $cartItem->id;
                    $orderItem->product_id = $cartItem->product_id;
                    $orderItem->price = $cartItem->price;
                    $orderItem->amount = $cartItem->amount;
                    $orderItem->size = $cartItem->size;
                    $orderItem->images = $cartItem->images;
                    $orderItem->save();
                } else {
                    $order = new Order();
                    $order->user_id = $cartItem->product->agent_id;
                    $order->app_user_id = $app_user_id;
                    $order->description = '';
                    $order->transaction_id = request('transaction_id');
                    $order->cart_id = $cart_id;
                    $order->type = 1;
                    $order->order_number = mt_rand(10000, 99999);
                    $order->save();
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->cart_item_id = $cartItem->id;
                    $orderItem->product_id = $cartItem->product_id;
                    $orderItem->price = $cartItem->price;
                    $orderItem->amount = $cartItem->amount;
                    $orderItem->size = $cartItem->size;
                    $orderItem->images = $cartItem->images;
                    $orderItem->save();
                }
            }
            $cart->status = 1;
            $cart->save();
        }

        $ordersOfTransaction = Order::where('transaction_id', request('transaction_id'))->get();
        $orderFees = Setting::where('key', 'order_fees')->first();
        $feesForEachOrder = $orderFees->value / $ordersOfTransaction->count();
        foreach ($ordersOfTransaction as $order) {
            $order->fees = $feesForEachOrder;
            $order->save();
        }
        return redirect()->route('order.orderSuccess');
    }

    public function orderSuccess()
    {
        return view('client.orderSuccess');
    }

    public function processing($id)
    {
        $order = Order::find($id);
        $order->status = 1;
        $order->save();
        return back();
    }

    public function approve($id)
    {
        $order = Order::find($id);
        $order->status = 2;
        $order->save();
        return back();
    }

    public function cancel($id)
    {
        $order = Order::find($id);
        $order->status = 3;
        $order->save();
        if ($order->type == 1)
            $response = Http::asForm()->post('https://www.paytabs.com/apiv2/refund_process', [
                'merchant_email' => 'mostafa.fathi.hilles+test@gmail.com',
                'secret_key' => 'rcqiNWZaFCmNA4G2fXWkfXq98IfHtS4V4udSqd26QpTrtztIxp3MntWwcbeG3iB8NP0e32kTsdkWNOFkeI7Bc38pem5iEHqswqGz',
                'refund_amount' => $order->total_amount_with_fees,
                'refund_reason' => 'cancel order',
                'transaction_id' => $order->transaction_id,
                'order_id' => $order->id,
            ]);
        //dd($response->body(),$response->json());
        return back();
    }

    public function delivery(Request $request, $id)
    {
        $order = Order::find($id);
        $order->delivery_amount = $request->delivery_amount ?? 0;
        $order->delivery_date = $request->delivery_date ?? null;
        $order->save();
        return back();
    }

    public function redirect(Request $request, $id)
    {
        $order = Order::find($id);
        $order->user_id = $request->agent_id ?? 0;
        $order->save();
        return back();
    }
}
