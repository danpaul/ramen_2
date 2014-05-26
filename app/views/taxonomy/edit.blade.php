<?php

	function recurseTree($tree)
	{
		if( !empty($tree) )
		{
			echo '<li>';
				echo $tree['name'];
				if( !empty($tree['children']) )
				{
					echo '<ul>';
						recurseTree($tree['children']);
					echo '</ul>';
				}
			echo '</li>';
		}
	}

?>

@foreach( Config::get('taxonomy.categoryTypes' ) as $taxonomyType )
	<ul>{{ $taxonomyType }}
		<?php recurseTree($categoryTrees[$taxonomyType]); ?>
	</ul>
@endforeach