<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Validate;
use App\Http\Controllers\Controller;
use App\Models\Order\Cart;
use App\Models\Order\CartItem;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function showUserCart()
    {
        if (auth('sanctum')->user()->id == 1 or auth('sanctum')->user()->id == 2)
            return response()->json(['message' => 'visitor can not show  cart'], 422);

        $carts = auth('sanctum')->user()->carts()->where('status', 0)->orderBy('id', 'desc')->first();
        if (!$carts)
            return response()->json(['message' => 'Cart empty', 'status' => 0], 200);

        return response()->json(['data' => $carts], 200);
    }

    public function showListUserCart()
    {
        if (auth('sanctum')->user()->id == 1 or auth('sanctum')->user()->id == 2)
            return response()->json(['message' => 'visitor can not show  cart'], 422);

        $carts = auth('sanctum')->user()->carts()->where('status', 0)->orderBy('id', 'desc')->get();
        if (count($carts) == 0)
            return response()->json(['message' => 'No carts added before yet!', 'status' => 0], 200);

        return response()->json(['data' => $carts], 200);
    }

    public function makeUserCart(Request $request)
    {
        if (auth('sanctum')->user()->id == 1 or auth('sanctum')->user()->id == 2)
            return response()->json(['message' => 'visitor can not add to cart'], 422);

        $rules = [
            'product_id' => 'required',
            'amount' => 'required'
        ];
        $validator = Validate::validateRequest($request, $rules);
        if ($validator !== 'valid') return $validator;

        $cart = auth('sanctum')->user()->carts()->where('status', 0)->orderBy('id', 'desc')->first();
        if(!$cart){
            $cart = new Cart();
            $cart->app_user_id = auth('sanctum')->user()->id;
            $cart->save();
        }
        $product = Product::find($request->product_id);
        if (!$product) return response()->json(['message' => 'Product not found', 'status' => 0], 422);
        $cartItem = new CartItem();
        $cartItem->cart_id = $cart->id;
        $cartItem->product_id = $product->id;
        $cartItem->price = $product->price;
        $cartItem->amount = $request->amount;
        $cartItem->size = $product->size;
        if ($request->has('upload_images') and $request->upload_images != null) {
            $imageNames = [];
            foreach ($request->upload_images as $image) {
                $imageName = $image->store('public/carts');
                array_push($imageNames, $imageName);
            }
            $cartItem->images = $imageNames;
        }
        if ($request->has('facebook_images') and count($request->facebook_images) > 0 and $request->facebook_images[0] != '') {
            $imageNames = $cartItem->images ?? [];
            foreach ($request->facebook_images as $image) {
                array_push($imageNames, '*facebook*' . $image);
            }
            $cartItem->images = $imageNames;
        }
        $cartItem->save();
        $cart = auth('sanctum')->user()->carts()->where('status', 0)->orderBy('id', 'desc')->first();
        return response()->json(['data' => $cart], 200);
    }

    public function updateCartItem(Request $request, $cart_item_id)
    {
        if (auth('sanctum')->user()->id == 1 or auth('sanctum')->user()->id == 2)
            return response()->json(['message' => 'visitor can not add to cart'], 422);

        $rules = [
            'amount' => 'required'
        ];
        $validator = Validate::validateRequest($request, $rules);
        if ($validator !== 'valid') return $validator;


        $cartItem = CartItem::find($cart_item_id);
        if (!$cartItem) return response()->json(['message' => 'Cart item not found', 'status' => 0], 422);

        $cartItem->amount = $request->amount;

        $cartItem->save();
        $cart = auth('sanctum')->user()->carts()->where('status', 0)->orderBy('id', 'desc')->first();
        return response()->json(['data' => $cart], 200);
    }

    public function removeCartItem($id)
    {
        if (auth('sanctum')->user()->id == 1 or auth('sanctum')->user()->id == 2)
            return response()->json(['message' => 'visitor can not remove from  cart'], 422);

        $item = CartItem::destroy($id);
        if (!$item) return response()->json(['message' => 'Cart item not found', 'status' => 0], 200);

        $cart = auth('sanctum')->user()->carts()->where('status', 0)->orderBy('id', 'desc')->first();

        return response()->json(['data' => $cart, 'status' => 1], 200);
    }

    public function setCartDeliveryLocation(Request $request, $id)
    {
        $rules = [
            'delivery_location' => 'required',
        ];
        $validator = Validate::validateRequest($request, $rules);
        if ($validator !== 'valid') return $validator;

        if (auth('sanctum')->user()->id == 1 or auth('sanctum')->user()->id == 2)
            return response()->json(['message' => 'visitor can not set location'], 422);

        $item = Cart::find($id);
        if (!$item) return response()->json(['message' => 'Cart item not found', 'status' => 0], 200);

        $item->delivery_location = $request->delivery_location;
        $item->save();

        return response()->json(['message' => 'Delivery location set successfully', 'status' => 1], 200);
    }

}
