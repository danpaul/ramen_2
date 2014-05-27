<?php

class TaxonomyController extends BaseController {

	public function getAll()
	{
		// $categoryTrees = Category::getCategoryTrees();

		// var_dump(Config::get('taxonomy.categoryTypes'));

var_dump(Category::getTrees());
die();


	}

	public static function postEditCategory($id)
	{
		$category = Category::find($id);
		self::setParent();

		if( $category === NULL )
		{
			return Redirect::back()->withErrors('Invalid category id.');
		}

		//update name
		$category->name = Input::get('name', '');
		$category->save();

		//update parent
		Category::setParent($id, Input::get('parent'));
		// $category->parent = Input::get('parent', NULL);

		
		return Redirect::back();

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

		self::setParent();

		$category = new Category;

		$category->type = $categoryType;
		$category->parent = Input::get('parent');
		$category->name = Input::get('name', '');

		$category->save();

		return Redirect::back();
	}

	private static function setParent()
	{
		if( !empty(Input::get('parent'))
			&& !Category::find(Input::get('parent'))->exists )
		{
			Input::set('parent', NULL);
		}

	}
}