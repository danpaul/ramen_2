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

var_dump($categoryLists);
die();

?>

@foreach( Config::get('taxonomy.categoryTypes' ) as $taxonomyType )
	<ul>{{ $taxonomyType }}
		<?php recurseTree($categoryTrees[$taxonomyType]); ?>
	</ul>
@endforeach

{{ Form::open(array('action' => 'TaxonomyController@postAdd' )) }}

	Name: <input type="text" name="name">
	Parent:
		<select name="parent_id">
			<option value=""></option>
			<?php
				// foreach ($_category_list as $category) {
				// 	echo '<option value="'. $category['id']. '">'. $category['name']. '</option>';					
				// }
			?>
		</select>

	{{ Form::submit('Add') }}

{{ Form::close() }}