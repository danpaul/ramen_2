<?php

class CatalogController extends BaseController {

	public static function getHome()
	{

		//categories

		//20 most recent products

		$products = Product::with('productImages')->orderBy('created_at', 'DESC')->paginate(20);

// dd(DB::getQueryLog());
// dd($products);

		return View::make(
			'catalog.home',
			array(
				'products' => $products,
				// 'tags' => Tag::getAll(),
				'categoryTrees' => Category::getTrees(),
				'categoryLists' => Category::getLists(),
				// 'productImages' => $product->productImages
			)
		);

	}

}