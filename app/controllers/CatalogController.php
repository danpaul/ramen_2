<?php

class CatalogController extends BaseController {

	const PAGINATE_AMOUNT = 20;

	public static function getHome()
	{

		//categories

		//20 most recent products

		$products = Product::with('productImages')->orderBy('created_at', 'DESC')->paginate(self::PAGINATE_AMOUNT);

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

	public static function getCategory($id)
	{
		$products = DB::table('category_product')	
			->join('products', 'category_product.id', '=', 'products.id')
			->whereIn('category_product.category_id', Category::getCategoryAndChildren($id))
			->paginate(self::PAGINATE_AMOUNT);

foreach ($products as $product)
{
	var_dump($product->name);
}

	}

}