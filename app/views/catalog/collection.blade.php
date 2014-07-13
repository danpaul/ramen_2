<?php echo $menu; ?>

@foreach($products as $product)

	<a href="<?php echo action('CatalogController@getProduct', $product->id); ?>">

		{{{ $product->name }}}

	</a>
	<br>

@endforeach