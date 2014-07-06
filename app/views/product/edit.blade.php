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



<h1>Categories</h1>



{{ Form::open(array('action' => array('ProductController@postUpdateCategories', $product['id']) )); }}

	@foreach( Config::get('taxonomy.categoryTypes' ) as $categoryType )

		<ul><h2>{{ $categoryType }}</h2>

			<?php Ramen::recurseTree(
					$categoryTrees[$categoryType],
					function($category) use($categoryLists, $categoryType, $product){ ?>

				<div class="taxonomy-edit">

					{{{ $category['name'] }}}

					@if( $product->hasCategory($category['id']))

						{{ Form::checkbox('ids[]', $category['id'], array('checked' => 'true')); }}

					@else

						{{ Form::checkbox('ids[]', $category['id']); }}

					@endif
					

				</div>
				
			<?php }); ?>

		</ul>

		<hr>

	@endforeach

	{{ Form::submit('Update'); }}

{{ Form::close(); }}


<h1>Tags</h1>

{{ Form::open(array('action' => array('ProductController@postUpdateTags', $product->id) )); }}

	@foreach( Config::get('taxonomy.tagTypes' ) as $tagType )

		<ul><h2>{{ $tagType }}</h2>	

				@foreach( $tags[$tagType] as $tag )

						<li>
							{{ Form::label($tag->name. ':'); }}
							@if( $product->hasTag($tag->id))

								{{ Form::checkbox('ids[]', $tag->id, array('checked' => 'true')); }}

							@else


								{{ Form::checkbox('ids[]', $tag->id); }}

							@endif
						</li>

				@endforeach

		</ul>

		<hr>

	@endforeach

	{{ Form::submit('Update Tags'); }}

{{ Form::close(); }}

<hr>

<h1>Images</h1>

<script type="text/template" id="product-image-field-template">
	Path: <input class="image-field-path" type="text" name="">
	Order: <input class="image-field-order" type="text" name="">	
	Delete: <input class="image-field-delete" type="checkbox" name="">
	<br>
</script>

{{ Form::open(array('action' => array('ProductController@postAddImages', $product->id), 'id' => 'product-image-form', 'data-fieldcount' => count($productImages) )); }}

	<div class="fields">

		<?php $count = 0 ?>

		@foreach( $productImages as $image )

			Path: <input type="text" name="image[<?php echo $count; ?>][path]" value="<?php echo $image->path; ?>">
			Order: <input type="text" name="image[<?php echo $count; ?>][order]" value="<?php echo $image->order; ?>">
			Delete: <input type="checkbox" name="image[<?php echo $count; ?>][delete]">
			<br>

			<?php $count += 1; ?>

			<!-- Image: {{ Form::text('images[]', $image->path) }}<br> -->

		@endforeach

	</div>

	<button type="button" id="add-product-image-button">Add Image</button>

	{{ Form::submit('Save Images'); }}

{{ Form::close(); }}


<?php var_dump(count($productImages)); ?>


@include('partials.foot')