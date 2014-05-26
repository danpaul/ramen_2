<?php


/** Helper methods for Ramen app */
class Ramen
{
	/** used for displaying category tree */
	public static function recurseTree($trees)
	{
		if( is_array($trees) )
		{
			foreach( $trees as $tree )
			{
				echo '<li>';
					echo $tree['name'];
					if( !empty($tree['children']) )
					{
						echo '<ul>';
							self::recurseTree($tree['children']);
						echo '</ul>';
					}
				echo '</li>';
			}
		}
	}

}