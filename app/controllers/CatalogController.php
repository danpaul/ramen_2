<?php

class CatalogController extends BaseController {

	const PAGINATE_AMOUNT = 20;

	public static function getHome()
	{

		$products = Product::with('productImages')->orderBy('created_at', 'DESC')->paginate(self::PAGINATE_AMOUNT);

		return View::make(
			'catalog.home',
			array(
				'products' => $products,
				'categoryTrees' => Category::getTrees(),
				'categoryLists' => Category::getLists(),
			)
		);

	}

	public static function getCategory($id)
	{
		$products = DB::table('category_product')	
			->join('products', 'category_product.id', '=', 'products.id')
			->whereIn('category_product.category_id', Category::getCategoryAndChildren($id))
			->paginate(self::PAGINATE_AMOUNT);

		return View::make(
			'catalog.collection',
			array('products' => $products)
		)
			->nest('menu', 'partials.main_menu', array(
				'categoryTrees' => Category::getTrees(),
				'categoryLists' => Category::getLists()
			)
		);
	}

	public static function getProduct($id)
	{
		return View::make(
			'catalog.product', array('product' => Product::find($id))
		)
			->nest('menu', 'partials.main_menu', array(
				'categoryTrees' => Category::getTrees(),
				'categoryLists' => Category::getLists()
			));
	}

}