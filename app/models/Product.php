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
}