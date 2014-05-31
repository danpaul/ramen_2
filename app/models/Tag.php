<?php

class Tag extends Eloquent {

	public $timestamps = false;

	public static function getAll()
	{
		$tags = array();

		foreach( Config::get('taxonomy.tagTypes') as $type )
		{
			$tags[$type] = self::where('type', '=', $type)->get();
		}

		return $tags;

	}

}