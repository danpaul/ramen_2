<?php

class TaxonomyController extends BaseController {

/*******************************************************************************

			GENERAL

*******************************************************************************/

	public function getEdit()
	{
		return View::make('taxonomy.edit', array(
			'categoryTrees' => Category::getTrees(),
			'categoryLists' => Category::getLists(),
			'tags' => Tag::getAll()
		));
	}


/*******************************************************************************

			CATEGORIES

*******************************************************************************/

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


/*******************************************************************************

			TAGS

*******************************************************************************/

	public static function postAddTag($type)
	{
		if( !self::isValidTag($type) )
		{
			return Redirect::back()->withErrors('Invalid tag type.');
		}

		$tag = new Tag();
		$tag->type = $type;
		$tag->name = Input::get('name', '');
		$tag->save();

		return Redirect::back();

	}

	public static function postEditTag($id)
	{
		$tag = Tag::find($id);
		$tag->name = Input::get('name', '');
		$tag->save();
		return Redirect::back();
	}

	public static function postDeleteTag($id)
	{
		$tag = Tag::find($id);
		$tag->delete();
		return Redirect::back();
	}

	private static function isValidTag($type)
	{
		return in_array($type, Config::get('taxonomy.tagTypes') );
	}
}