<?php

namespace App\Services;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\User;
use Exception;
use Str;

class CartService {

    public function __construct(
        public UserService $userService,
        public Cart $cart
    ) {}

    /**
     * addItemToCart
     * Function will be used to add item in the cart
     *
     * @param  array $arrData
     * @return mixed
     */
    public function addItemToCart($arrData) {
        $userDetails = $this->userService->getLoggedInUser();
        if($userDetails) {
            return $this->addItemBasedOnUserId($arrData, $userDetails);
        } else {
            if(!isset($arrData['session_id'])) {
                $arrData['session_id'] = Str::uuid();
            }
            return $this->save($arrData);
        }
    }

    /**
     * save
     * Function will save item in the cart
     *
     * @param  array $arrData
     * @return mixed
     */
    public function save($arrData) {
        $this->validateCartData($arrData);
        $this->checkIfProductIsAlreadyInCart($arrData);
        $this->cart->fill($arrData)->save();
        return $this->getCartItemDetails($this->cart->id);
    }

    /**
     * addItemBasedOnUserId
     * Function will be used to save item for logged in user
     * @param  array $arrData
     * @param  User $userDetails
     * @return mixed
     */
    public function addItemBasedOnUserId($arrData, $userDetails) {
        $arrData['user_id'] = $userDetails->id;
        return $this->save($arrData);
    }

    /**
     * checkIfProductIsAlreadyInCart
     * Function will be used to determine, if given item is present in cart or not
     *
     * @param  array $arrData
     * @return mixed
     */
    public function checkIfProductIsAlreadyInCart($arrData) {
        if(isset($arrData['session_id'])) {
            $cartItem = Cart::where(['session_id' => $arrData['session_id'], 'product_id' => $arrData['product_id']])->first();
            if($cartItem) {
                throw new Exception("Product is already added in the cart");
            }
        } else {
            $cartItem = Cart::where(['user_id' => $arrData['user_id'], 'product_id' => $arrData['product_id']])->first();
            if($cartItem) {
                throw new Exception("Product is already added in the cart");
            }
        }
    }

    /**
     * getCartItemDetails
     * Function will be used to get item details
     *
     * @param  int $cartId
     * @return mixed
     */
    public function getCartItemDetails($cartId) {
        return new CartResource(Cart::whereId($cartId)->with(['product', 'product.category'])->first());
    }

    /**
     * validateCartData
     * Function will be used to identify if cart item can be saved or not
     *
     * @param  array $arrData
     * @return mixed
     */
    public function validateCartData($arrData) {
        if(!(isset($arrData['user_id']) || isset($arrData['session_id']))) {
            throw new Exception("Unable to save item to cart");
        }
    }

    /**
     * deleteItem
     * Function will be used to remove item from the cart
     *
     * @param  Cart $cart
     * @return boolean
     */
    public function deleteItem($cart) {
        $cart->delete();
        return true;
    }

    /**
     * updateCartItem
     * Function will be used to update cart item details
     *
     * @param  Cart $cart
     * @param  array $arrData
     * @return mixed
     */
    public function updateCartItem($cart, $arrData) {
        $cart->fill($arrData)->save();
        return $this->getCartItemDetails($cart->id);
    }

    /**
     * getAllCartItems
     * Function will be used to get all items from the cart
     *
     * @param  string $sessionId
     * @return array
     */
    public function getAllCartItems($sessionId) {
        $userDetails = $this->userService->getLoggedInUser();
        if($userDetails) {
            return $this->getCartItemsBasedOnUser($userDetails->id);
        } else {
            return $this->getCartItemsBasedOnSession($sessionId);
        }
    }

    /**
     * getCartItemsBasedOnUser
     * Function will be used to get items based on user id
     * @param  int $userId
     * @return array
     */
    public function getCartItemsBasedOnUser($userId) {
        return CartResource::collection(Cart::where("user_id", $userId)->get());
    }

    /**
     * getCartItemsBasedOnSession
     * Function will be used to get cart items based on session
     *
     * @param  string $sessionId
     * @return array
     */
    public function getCartItemsBasedOnSession($sessionId) {
        return CartResource::collection(Cart::where("session_id", $sessionId)->get());
    }
}
