{{ Form::model($product, array('action' => array('ProductController@postUpdate', $product->id))); }}

	{{ Form::label('name', 'Name: '); }}
	{{ Form::text('name'); }}

	{{ Form::label('sku', 'SKU: '); }}
	{{ Form::text('sku'); }}

	{{ Form::label('description', 'Description: '); }}
	{{ Form::textarea('description'); }}

	{{ Form::label('price', 'Price: '); }}
	{{ Form::text('price'); }}

	{{ Form::label('inventory', 'Inventory: '); }}
	{{ Form::text('inventory'); }}

	{{ Form::submit('Update'); }}

{{ Form::close(); }}



<h1>Tags</h1>

{{ Form::open(array('action' => array('ProductController@postUpdateTags', $product->id) )); }}

	@foreach( Config::get('taxonomy.tagTypes' ) as $tagType )

		<ul><h2>{{ $tagType }}</h2>	

				@foreach( $tags[$tagType] as $tag )

						<li>
							{{ Form::label($tag->name. ':'); }}
							{{ Form::checkbox('ids[]', $tag->id); }}
						</li>

				@endforeach

		</ul>

		<hr>

	@endforeach

	{{ Form::submit('Update Tags'); }}

{{ Form::close(); }}

