
	@foreach( Config::get('taxonomy.categoryTypes' ) as $categoryType )

		<ul><h2>{{ $categoryType }}</h2>

			<?php Ramen::recurseTree(
					$categoryTrees[$categoryType],
					function($category) { ?>
<!-- ProductController@postUpdate -->
				<a href="{{{ action('CatalogController@getCategory', $category['id']) }}}">

					{{{ $category['name'] }}}

				</a>

<?php // var_dump($category['name']); ?>
				
			<?php }); ?>

		</ul>

		<!-- <hr> -->

	@endforeach


<!-- dd($categoryTrees); -->
<!-- dd($products); -->