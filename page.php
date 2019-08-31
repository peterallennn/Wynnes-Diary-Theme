<?php
/**
 * The template for displaying all pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Wynne\'s_Diary
 */
global $post;

get_header();
?>
	<div class="post-body">
		<div class="post-content group">
			<?= apply_filters('the_content', $post->post_content) ?>
		</div>
		<a href="#" class="back-to-top"></a>
	</div>
	<span class="post-body-bottom"></span>

<?php
get_sidebar();
get_footer();
