<?php

/******************************************************************************/

class Product extends Eloquent {
	protected $fillable = array('name', 'sku', 'description', 'price', 'inventory');
	protected $attributes = array(
		'name' => '',
		'sku' => '',
		'description' => '',
		'price' =>0.00,
		'inventory' => 0
	);
	protected $categoryArray = NULL;
	protected $tagArray = NULL;

	public function categories()
	{
		return $this->belongsToMany('Category');
	}

	public function tags()
	{
		return $this->belongsToMany('Tag');
	}

	public function hasCategory($categoryId)
	{
		if(!$this->categoryArray)
		{
			$this->categoryArray = array();
			foreach( $this->categories as $category)
			{
				array_push($this->categoryArray, $category->id);
			}
		}
		return in_array((int)$categoryId, $this->categoryArray);
	}

	public function hasTag($tagId)
	{
		if(!$this->tagArray)
		{
			$this->tagArray = array();
			foreach( $this->tags as $tag)
			{
				array_push($this->tagArray, $tag->id);
			}
		}
		return in_array((int)$tagId, $this->tagArray);
	}


}