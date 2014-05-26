<?php

class TaxonomyController extends BaseController {

	public function getAll()
	{
		// $categoryTrees = Category::getCategoryTrees();

		// var_dump(Config::get('taxonomy.categoryTypes'));

var_dump(Category::getTrees());
die();


	}

	public function getEdit()
	{
		return View::make('taxonomy.edit', array(
			'categoryTrees' => Category::getTrees(),
			'categoryLists' => Category::getLists()
		));
	}

	public function postAdd()
	{
echo 'add';
	}

}