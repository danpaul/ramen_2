<?php

/******************************************************************************/

class Product extends Eloquent {
	protected $fillable = array('name', 'sku', 'description', 'price', 'inventory');
}