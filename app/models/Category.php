<?php

/******************************************************************************/

class Category extends Eloquent {

	/**
	* returns an array of cached variables
	*
	*  cached variables:
	*    'categoryTrees' => nested array of category trees. indexed by type
	*    'categoryLists' => array of flattened category trees, indexed by type
	*/
	public static function getCacheVariables()
	{
		return array('categoryTrees', 'categoryLists');
	}

	public static function boot()
	{
		parent::boot();

		self::created(function($n){ self::clearCache(); });
		self::updated(function($n){ self::clearCache(); });
		self::saved(function($n){ self::clearCache(); });
		self::deleted(function($n){ self::clearCache(); });
		self::restored(function($n){ self::clearCache(); });

	}

	public static function clearCache()
	{
		foreach( self::getCacheVariables() as $cacheKey )
		{
			Cache::forget($cacheKey);
		}
	}

	/**
	* @return - An arry of category trees (nested arrays) containing all 
	*    types defined in the 'taxonomy' config file.
	*/
	public static function getTrees()
	{
		if( !(Cache::has('categoryTrees')) )
		{
			$categoryTress = array();
			foreach( Config::get('taxonomy.categoryTypes' ) as $categoryType )
			{
				$categoryTrees[$categoryType] = self::getTree($categoryType);
			}
			Cache::forever('categoryTrees', $categoryTrees);
		}
		return Cache::get('categoryTrees');
	}

	/**
	*  Takes the category type as string and returns an array reprsenting the
	*    Category hierarchy.
	*    
	*  @param - $type - String - The category typ to get
	*
	*  @return - Array - Returns an array representing the category hierarchy.
	*    The first level of the array is the top level of the hierarch. Each
	*    array element contains a `subcategories` key with an array of
	*    categories or an empty array if there are no sub-categories.
	*/
	public static function getTree($type)
	{
		$categories = self::where('type', '=', $type)->get()->toArray();

	    $map = array();
	    $tree = array();

	    foreach( $categories as &$category )
	    {
	        $category['children'] = array();
	        $map[$category['id']] = &$category;
	    }

	    foreach( $categories as &$category )
	    {
	    	if( $category['parent'] !== NULL )
	    	{
	    		$map[$category['parent']]['children'][] = &$category;
	    	}	        
	    }

	    foreach( $map as $index => $map_category )
	    {
	    	if( $map_category['parent'] === NULL )
	    	{
	    		$tree[$index] = $map_category;
	    	}
	    }

	    return $tree;
	}

	public static function getList(&$trees)
	{

		$list = array();

		foreach( $trees as $tree )
		{
			$list[$tree['id']] = $tree;

			$list[$tree['id']] = $tree['name'];

			if( !empty($tree['children']) )
			{
				$children = self::getList($tree['children']);
				$list = $list + $children;
			}				
		}

		return $list;
	}

	/**
	*  @return - A 2D array indexed by category type. Each category type array
	*    contains an array of categories belonging to that type of the form
	*    id => type
	*/
	public static function getLists()
	{
		if( !(Cache::has('categoryLists')) )
		{
			$categoryTrees = self::getTrees();
			$categoryLists = array();

			foreach( Config::get('taxonomy.categoryTypes' ) as $categoryType )
			{
				$categoryLists[$categoryType] = self::getList($categoryTrees[$categoryType]);
			}

			Cache::forever('categoryLists', $categoryLists);
		}
		return Cache::get('categoryLists');
	}

}