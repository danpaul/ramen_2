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

}