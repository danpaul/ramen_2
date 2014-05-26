@foreach( Config::get('taxonomy.categoryTypes' ) as $categoryType )

	<ul>{{ $categoryType }}
		<?php Ramen::recurseTree($categoryTrees[$categoryType]); ?>
	</ul>

	{{ Form::open(array('action' => array('TaxonomyController@postAddCategory', $categoryType ))) }}

		{{ Form::label('name', 'Name: ') }}
		{{ Form::text('name') }}

		{{ Form::label('parent', 'Parent: ') }}

		{{ Form::select('parent', $categoryLists[$categoryType]) }}
		
		{{ Form::submit('Add') }}

	{{ Form::close() }}

@endforeach