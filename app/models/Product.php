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
	protected $tagArray = NULL;

	public function tags()
	{
		return $this->belongsToMany('Tag');
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
// var_dump($this->tagArray);
// die();
	}


}