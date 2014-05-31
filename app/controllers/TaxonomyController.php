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
		
		if( $category === NULL )
		{
			return Redirect::back()->withErrors('Invalid category id.');
		}

		//update name
		$category->name = Input::get('name', '');
		$category->save();

		//update parent
		Category::setParent($id, self::getParent());

		return Redirect::back();

	}

	public function getEdit()
	{
		return View::make('taxonomy.edit', array(
			'categoryTrees' => Category::getTrees(),
			'categoryLists' => Category::getLists()
		));
	}

	public static function postDeleteCategory($id)
	{
		$category = Category::find($id);
		
		if( $category === NULL )
		{
			return Redirect::back()->withErrors('Invalid category id.');
		}

		$category->remove();
		return Redirect::back();
	}

	public function postAddCategory($categoryType)
	{
		// redirect with error if not exists
		if( !in_array($categoryType, Config::get('taxonomy.categoryTypes') ))
		{
			return Redirect::back()->withErrors('Invalid category type.');
		}

		$category = new Category;

		$category->type = $categoryType;
		$category->parent = self::getParent();
		$category->name = Input::get('name', '');

		$category->save();

		return Redirect::back();
	}

	private static function getParent()
	{
		if( empty(Input::get('parent'))
			|| !Category::find(Input::get('parent'))->exists )
		{
			return NULL;			
		}
		return Input::get('parent');

	}
}