<?php echo View::make('partials.head'); ?>
<?php echo $menu; ?>

	<table>
		<tr>
			<th>Item</th>
			<th>Price</th>
			<th>Quantity</th>
			<th>Subtotal</th>
		</tr>

		@foreach( $cartContents as $rowId => $item )
			<tr>
				<th>{{ $item->name }}</th>
				<th>{{ $item->price }}</th>
				<th>{{ $item->qty }}</th>
				<th>{{ $item->subtotal }}</th>
			</tr>
		@endforeach			
	</table>

<?php echo View::make('partials.foot'); ?>