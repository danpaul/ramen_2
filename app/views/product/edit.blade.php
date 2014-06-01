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