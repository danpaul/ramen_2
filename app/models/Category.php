<?php

class Category extends Eloquent {

	protected $table = 'Categories';
	public $timestamps = FALSE;

	// http://stackoverflow.com/questions/4843945/php-tree-structure-for-categories-and-sub-categories-without-looping-a-query
	public static function getCategoryTree($type)
	{

		$categories = self::where('category_type', '=', $type)->get()->toArray();
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