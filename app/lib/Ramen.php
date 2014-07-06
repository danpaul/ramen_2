<?php


/** Helper methods for Ramen app */
class Ramen
{
	/** used for displaying category tree */
	public static function recurseTree($trees, $callback = NULL)
	{
		if( is_array($trees) )
		{
			foreach( $trees as $tree )
			{
				echo '<li>';
					$callback($tree);
					if( !empty($tree['children']) )
					{
						echo '<ul>';
							self::recurseTree($tree['children'], $callback);
						echo '</ul>';
					}
				echo '</li>';
			}
		}
	}

}