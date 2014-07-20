@foreach( Config::get('taxonomy.categoryTypes' ) as $categoryType )

	<ul><h2>{{ $categoryType }}</h2>

		<?php Ramen::recurseTree(
				$categoryTrees[$categoryType],
				function($category) { ?>

			<a href="{{ action('CatalogController@getCategory', $category['id']) }}">
				{{{ $category['name'] }}}
			</a>
	
		<?php }); ?>

	</ul>

@endforeach

<a href="{{ action('UserController@getCheckout'); }}">
	Checkout
</a>

<br>