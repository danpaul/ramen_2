<?php

/******************************************************************************/

class Category extends Eloquent {

	protected static $categoryChildren;

public function products()
{
	return $this->hasMany('Product');
}

	/**
	* returns an array of cached variables
	*
	*  cached variables:
	*    'categoryTrees' => nested array of category trees. indexed by type
	*    'categoryLists' => array of flattened category trees, indexed by type
	*    'categoryChildren' => array indexed by category id of arrays of children
	*/
	public static function getCacheVariables()
	{
		return array('categoryTrees', 'categoryLists', 'categoryChildren');
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

	/**
	*  @return - an assoc. array indexed by category id. Each element contains an
	*    array of children of the given element.
	*/
	public static function getChildren()
	{
		if( empty(self::$categoryChildren) )
		{
			if( !(Cache::has('categoryChildren')) )
			{
				self::setChildren();
			}
			self::$categoryChildren = Cache::get('categoryChildren');
		}
		return self::$categoryChildren;
	}

	/**
	*  @param - category id
	*  @return - an array that category's children's ids
	*/
	public static function getCategorysChildren($id)
	{
		$children = self::getChildren();
		return $children[$id];
	}

	/**
	*  @param - category id
	*  @return - an array of category's children's ids plus $id
	*/
	public static function getCategoryAndChildren($id)
	{
		$all = self::getCategorysChildren($id);
		array_push($all, (int)$id);
		return $all;
	}

	/**
	*  Sets the cache for category children. Indexed by cat. id.
	*/
	private static function setChildren()
	{
		$children = array();
		$trees = self::getTrees();

		foreach( $trees as $tree )
		{
			foreach( $tree as $category )
			{
				self::buildChildren($children, $category);
			}
		}
		Cache::forever('categoryChildren', $children);
	}

	/**
	*  Builds the internal children array
	*/
	private static function buildChildren(&$childrenArray, &$category)
	{
			$childrenArray[$category['id']] = self::getCategoryChildren($category);

			if( !empty($category['children']) )
			{
				foreach( $category['children'] as &$child )
				{
					self::buildChildren($childrenArray, $child);
				}
			}
	}

	/**
	*  Recursive helper function to build children array.
	*/
	private static function getCategoryChildren(&$category)
	{
		$children = array();

		foreach( $category['children'] as &$child )
		{
			array_push($children, $child['id']);
			if( !empty($child['children']) )
			{
				$results = self::getCategoryChildren($child);
				foreach( $results as $result)
				{
					array_push($children, $result);
				}
			}
		}
		return $children;
	}

	/**
	* @return - returns TRUE if categoryTwo is a child of categoryOne
	*/
	public static function isChild($categoryOneId, $categoryTwoId)
	{
		$children = self::getChildren();
		return(in_array($categoryTwoId, $children[$categoryOneId]));
	}

	/**
	*  Takes valid $id and $parentId.
	*  Sets $id's parent to $parentId and ensures no category nodes are orphaned
	*  If new parent is a child of self, new parent moves to top level and self
	*    become a child of new parent
	*/
	public static function setParent($id, $parentId)
	{

		if( $id === $parentId ){ return NULL; }

		$child = self::find($id);

		if( $child === NULL ){ return NULL; }

		if( $parentId === NULL )
		{
			$child->parent = NULL;
			$child->save();
			return;
		}

		$parent = self::find($parentId);

		// if parent has changed
		if( $child->parent !== $parent->id )
		{
			$child->parent = $parent->id;

			//if parent is a child of category
			if( self::isChild($id, $parentId) )
			{
				$parent->parent = NULL;
				$parent->save();
			// if parent is not a child of category (parent doesn't change)
			}else{
				$child->parent = $parentId;
			}
			$child->save();
		}
	}

	/**
	*  Deletes that category and sets the children of that catgorie's parents
	*    to the parent of that category  
	*/
	public function remove()
	{
		$children = Self::where('parent', '=', $this->id)->get();

		foreach( $children as $child )
		{
			$child->parent = $this->parent;
			$child->save();
		}

		$this->delete();

	}

}