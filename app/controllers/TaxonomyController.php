<?php

class TaxonomyController extends BaseController {

	public function getAll()
	{
		$categoryTree = Category::getCategoryTree('foo');

var_dump($categoryTree);


die();


	}

	public function getAdd()
	{



		// View::make('taxonomy.add', array(
		// 	'categoryTree' => 
		// ));
	}

}