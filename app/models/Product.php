<?php

/******************************************************************************/

class Product extends Eloquent {

	protected $fillable = array('name', 'sku', 'description', 'price', 'inventory');
	protected $attributes = array(
		'name' => '',
		'sku' => '',
		'description' => '',
		'price' =>0.00,
		'inventory' => 0
	);

	protected $categoryArray = NULL;
	protected $tagArray = NULL;

	public function categories()
	{
		return $this->belongsToMany('Category');
	}

	public function tags()
	{
		return $this->belongsToMany('Tag');
	}

	public function productImages()
	{
		return $this->hasMany('ProductImage')->orderBy('order');
	}

	public function hasCategory($categoryId)
	{
		if(!$this->categoryArray)
		{
			$this->categoryArray = array();
			foreach( $this->categories as $category)
			{
				array_push($this->categoryArray, $category->id);
			}
		}
		return in_array((int)$categoryId, $this->categoryArray);
	}

	public function hasTag($tagId)
	{
		if(!$this->tagArray)
		{
			$this->tagArray = array();
			foreach( $this->tags as $tag)
			{
				array_push($this->tagArray, $tag->id);
			}
		}
		return in_array((int)$tagId, $this->tagArray);
	}

	/**
	*  Iterate through cart and update prices if they are inaccurate.
	*  If $isCheck === TRUE, value will return FALSE if prices in cart
	*    do not match price in DB.
	*/
	public static function updateCart($isCheck = FALSE)
	{
		$productIds = array();
		$productPrices = array();
		$isSame = TRUE;

		foreach( Cart::content() as $rowId => $cartItem ){
			array_push($productIds, $cartItem->id);
		}

		$products = Product::whereIn('id', $productIds)->get();

		foreach( $products as $product )
		{
			$productPrices[$product->id] = $product->price;
		}

		foreach( Cart::content() as $rowId => $cartItem ){
			$price = $productPrices[$cartItem->id];
			if( $cartItem->price !== $price )
			{
				$isSame = FALSE;
				Cart::update($rowId, array('price' => $price ));
			}
		}

		return $isSame;

	}


}