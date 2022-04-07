<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Validate;
use App\Http\Controllers\Controller;
use App\Models\Data\Setting;
use App\Models\Order\Cart;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function showUserOrders()
    {
        $orders = auth('sanctum')->user()->orders;

        return response()->json(['data' => $orders], 200);
    }

    public function showOrder($id)
    {
        $order = auth('sanctum')->user()->orders->where('id', $id);

        return response()->json(['data' => $order], 200);
    }

    public function makeCashOrder($cart_id)
    {
        $app_user_id = auth('sanctum')->user()->id;
        $cart = Cart::find($cart_id);
        if ($cart) {
            foreach ($cart->items as $cartItem) {
                $order = Order::where('user_id', $cartItem->product->agent_id)
                    ->where('app_user_id', $app_user_id)
                    ->where('cart_id', $cart_id)
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
                    $order->cart_id = $cart_id;
                    $order->type = 2;
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
        $ordersOfTransaction = Order::where('cart_id', $cart_id)->get();
        $orderFees = Setting::where('key', 'order_fees')->first();
        $vatValue = ((intval($orderFees->value))/100) * $cart->total_amount;
        $feesForEachOrder = $vatValue / $ordersOfTransaction->count();
        foreach ($ordersOfTransaction as $order) {
            $order->fees = $feesForEachOrder;
            $order->save();
        }
        $ordersOfTransaction = Order::where('cart_id', $cart_id)->get();
        return response()->json(['data' => $ordersOfTransaction], 200);
    }
    public function makePayPalOrder(Request $request,$cart_id)
    {
        $rules = [
            'transaction_id' => 'required',
        ];
        $validator = Validate::validateRequest($request, $rules);
        if ($validator !== 'valid') return $validator;

        $app_user_id = auth('sanctum')->user()->id;
        $cart = Cart::find($cart_id);
        if ($cart) {
            foreach ($cart->items as $cartItem) {
                $order = Order::where('user_id', $cartItem->product->agent_id)
                    ->where('app_user_id', $app_user_id)
                    ->where('cart_id', $cart_id)
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
                    $order->transaction_id = $request->transaction_id;
                    $order->cart_id = $cart_id;
                    $order->type = 3;
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
        $ordersOfTransaction = Order::where('transaction_id', $request->transaction_id)->get();
        $orderFees = Setting::where('key', 'order_fees')->first();
        $vatValue = ((intval($orderFees->value))/100) * $cart->total_amount;
        $feesForEachOrder = $vatValue / $ordersOfTransaction->count();
        foreach ($ordersOfTransaction as $order) {
            $order->fees = $feesForEachOrder;
            $order->save();
        }
        $ordersOfTransaction = Order::where('cart_id', $cart_id)->get();
        return response()->json(['data' => $ordersOfTransaction], 200);
    }

    public function makeOrderRating(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order)
            return response()->json(['message' => 'Order not found'], 422);

        $order->rating = (($request->rate_value > 5) ? 5 : ($request->rate_value < 0 ? 0 : intval($request->rate_value)));
        $order->save();

        return response()->json(['data' => $order], 200);
    }
}
