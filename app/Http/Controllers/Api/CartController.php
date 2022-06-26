<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Cart;
use App\Services\CartService;
use App\Traits\OutputTrait;
use Exception;
use Illuminate\Http\Request;
use Auth;

class CartController extends Controller
{
    use OutputTrait;

    public function __construct(
        Public CartService $cartService
    ) {}

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sesssionId = $request->query("session_id");
        $cartItems = $this->cartService->getAllCartItems($sesssionId);
        return $this->sendSuccess("Cart items list", $cartItems);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCartRequest $request)
    {
        try {
            $cartItem = $this->cartService->addItemToCart($request->validated());
            return $this->sendSuccess("Item is added in the cart", $cartItem);
        } catch (Exception $exp) {
            return $this->sendError($exp);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCartRequest  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        try {
            $cartItem = $this->cartService->updateCartItem($cart, $request->validated());
            return $this->sendSuccess("Cart item updated successfully.", $cartItem);
        } catch (Exception $exp) {
            return $this->sendError($exp);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        try {
            $this->cartService->deleteItem($cart);
            return $this->sendSuccess("Cart item deleted successfully.");
        } catch (Exception $exp) {
            return $this->sendError($exp);
        }
    }
}
