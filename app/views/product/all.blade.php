<h1>Products</h1>

<table>
	<tbody>
		<tr>
			<th>Name</th>
			<th>SKU</th>
			<th>Description</th>
			<th>Price</th>
			<th>Inventory</th>
			<th>Edit</th>
		</tr>

		@foreach( $products as $product )

			<?php $editLink = action('ProductController@getEdit', $product->id); ?>

			<tr>
				<th>{{{ $product->name }}}</th>
				<th>{{{ $product->sku }}}</th>
				<th>{{{ $product->description }}}</th>
				<th>{{{ $product->price }}}</th>
				<th>{{{ $product->inventory }}}</th>
				<th>
					<a href="{{ action('ProductController@getEdit', $product->id) }}">Edit</a>
				</th>
			</tr>

		@endforeach

	</tbody>

</table>
