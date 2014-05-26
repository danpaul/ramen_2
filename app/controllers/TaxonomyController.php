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

	public function postAddCategory($categoryType)
	{
		// redirect with error if not exists
		if( !in_array($categoryType, Config::get('taxonomy.categoryTypes') ))
		{
			return Redirect::back()->withErrors('Invalid category type.');
		}

		// confirm parent exists or set to NULL
		if( !empty(Input::get('parent'))
			&& !Category::find(Input::get('parent'))->exists )
		{
			Input::set('parent', NULL);
		}

		// create category
		$category = new Category;

		$category->type = $categoryType;
		$category->parent = Input::get('parent');
		$category->name = Input::get('name', '');

		$category->save();

		return Redirect::back();

	}
}