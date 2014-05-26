<?php

/******************************************************************************/

class Category extends Eloquent {

	// private static $categoryTrees;


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
	        $category['subcategories'] = array();
	        $map[$category['id']] = &$category;
	    }

	    foreach( $categories as &$category )
	    {
	    	if( $category['parent'] !== NULL )
	    	{
	    		$map[$category['parent']]['subcategories'][] = &$category;
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

}