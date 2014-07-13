<?php echo View::make('partials.head'); ?>
<?php echo $menu; ?>

{{{ $product->name }}}

{{ Form::open(array('action' => array('CartController@postAdd', $product->id) )); }}

	{{ Form::label('quantity', 'Quantity'); }}
	{{ Form::text('quantity', 1); }}	
	{{ Form::submit('Add to Cart'); }}

{{ Form::close() }}