<?php

class ProductController extends BaseController {

	public function getAdd()
	{
		$categoryTree = Category::getCategoryTree('foo');

var_dump($categoryTree);


die();
		// var_dump($categories);;



		// require_once $GLOBALS['config']['models']. '/taxonomy.php';
		// $taxonomy = new Taxonomy_model();
		// View::$data['categories'] = $taxonomy->get_categories(self::PRODUCT_CATEGORY_TYPE);
		// View::$data['tags'] = $taxonomy->get_tags(self::PRODUCT_CATEGORY_TYPE);
		// require_once($GLOBALS['config']['views']. '/admin_product_add.php');

	}

}