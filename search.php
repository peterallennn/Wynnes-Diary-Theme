<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Wynne\'s_Diary
 */

get_header();
?>

	<div class="post-body">
		<div class="post-content group">
			<header class="page-header">
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'wynnes-diary' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</header><!-- .page-header -->

			<?php if ( have_posts() ) : ?>
				<?php
				echo '<ul class="search-results-body">';
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					?>
						<li>
							<h4>
								<a href="<?= the_permalink() ?>"><?= the_title() ?></a>
							</h4>
							<?php if(!empty(get_the_excerpt())) : ?>
								<?php the_excerpt() ?>
							<?php endif; ?>
						</li>
					<?php

				endwhile;

				echo '</ul>';

				echo '<div class="search-pagination">' . get_pagination_links() . '</div>';

			else :

				echo '<ul class="search-results-body"><li>No results found.</li></ul>';

			endif;
			?>

		</div>
	</div>

<?php
get_sidebar();
get_footer();
