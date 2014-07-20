<?php

// https://github.com/Crinsane/LaravelShoppingcart
class CartController extends BaseController {

	public function postAdd($id)
	{
		if( !$product = Product::find($id) )
		{
			return Redirect::back()->withErrors('Invalid id.');
		}

		Cart::add($id, $product->name, (int)Input::get('quantity', 1), $product->price);
		return Redirect::back();
	}

	public function postUpdateCart()
	{
		foreach( Input::get('items') as $rowId => $cartItem )
		{
			if( isset($cartItem['delete']) && $cartItem['delete'] === 'on' )
			{
				Cart::remove($rowId);
			}else{
				Cart::update($rowId, $cartItem['quantity']);
			}
		}
		return Redirect::back();
	}

}