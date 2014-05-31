<?php foreach( $errors->all() as $error ){ ?>

	<p><?php echo $error ?></p>

<?php } ?>

<h1>Categories</h1>

@foreach( Config::get('taxonomy.categoryTypes' ) as $categoryType )

	<ul><h2>{{ $categoryType }}</h2>
		<?php Ramen::recurseTree($categoryTrees[$categoryType], function($category) use($categoryLists, $categoryType){ ?>

			<div class="taxonomy-edit">

				{{ Form::open(array('action' => array('TaxonomyController@postEditCategory', $category['id']) )); }}

					{{ Form::label('name', 'Name: '); }}
					{{ Form::text('name', $category['name']); }}
					{{ Form::label('parent', 'Parent: '); }}
					{{
						Form::select(
							'parent',
							array('' => '') + $categoryLists[$categoryType],
							$category['parent']); 
					}}
					{{ Form::submit('Edit'); }}

				{{ Form::close(); }}

				{{ Form::open(array('class' => 'delete-form', 'action' => array('TaxonomyController@postDeleteCategory', $category['id']) )); }}
					{{ Form::submit('Delete'); }}
				{{ Form::close(); }}			


			</div>
			
		<?php }); ?>
	</ul>

	{{ Form::open(array('action' => array('TaxonomyController@postAddCategory', $categoryType ))); }}

		{{ Form::label('name', 'Name: '); }}
		{{ Form::text('name'); }}

		{{ Form::label('parent', 'Parent: '); }}

		{{
			Form::select(
				'parent',
				array('' => '') + $categoryLists[$categoryType],
				''
			); 
		}}
		
		{{ Form::submit('Add'); }}

	{{ Form::close(); }}

	<hr>

@endforeach

<h1>Tags</h1>

@foreach( Config::get('taxonomy.tagTypes' ) as $tagType )

	<ul><h2>{{ $tagType }}</h2>

		@foreach( $tags[$tagType] as $tag )

			<li>

				{{ Form::open(array('action' => array('TaxonomyController@postEditTag', $tag['id']) )); }}

					{{ Form::label('name', 'Name: '); }}
					{{ Form::text('name', $tag['name']); }}
					{{ Form::submit('Edit'); }}

				{{ Form::close(); }}

				{{ Form::open(array('class' => 'delete-form', 'action' => array('TaxonomyController@postDeleteTag', $tag['id']) )); }}
					{{ Form::submit('Delete'); }}
				{{ Form::close(); }}


			</li>

		@endforeach

	</ul>

	{{ Form::open(array('action' => array('TaxonomyController@postAddTag', $tagType ))); }}

		{{ Form::label('name', 'Name: '); }}
		{{ Form::text('name'); }}

		{{ Form::label('parent', 'Parent: '); }}
		
		{{ Form::submit('Add'); }}

	{{ Form::close(); }}

	<hr>

@endforeach