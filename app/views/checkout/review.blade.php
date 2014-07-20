<?php echo View::make('partials.head'); ?>
<?php echo $menu; ?>


	{{ Form::open(array(
		'action' => 'CartController@postUpdateCart',
		'method' => 'POST'
	)) }}

		<table>
			<tr>
				<th>Item</th>
				<th>Price</th>
				<th>Quantity</th>
				<th>Subtotal</th>
				<th>Remove</th>
			</tr>

			@foreach( $cartContents as $rowId => $item )
				<tr>
					<th>{{ $item->name }}</th>
					<th>{{ $item->price }}</th>
					<th>
						<input
							type="text"
							value="{{ $item->qty }}"
							name="items[{{ $rowId }}][quantity]"
						>
					</th>
					<th>{{ $item->subtotal }}</th>
					<th>
						<input
							type="checkbox"
							value="on"
							name="items[{{ $rowId }}][delete]"
						>
					</th>
				</tr>
			@endforeach			
		</table>
		{{ Form::submit('Update'); }}
	{{ Form::close(); }}

	<div>
		<a href="{{ action('UserController@getConfirmOrder') }}">
			Continue
		</a>
	</div>


<?php echo View::make('partials.foot'); ?>