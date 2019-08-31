<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Wynne\'s_Diary
 */

$years = get_terms([
	'taxonomy' => 'category',
	'hide_empty' => false,
	'exclude' => 1 // Exclude 'uncategorised'
]);

$hidden_years = [];
if(!is_user_logged_in()) {
	$hidden_years = json_decode(get_option('wdeditor_hidden_years'), true);
}

if(!empty(get_query_var('cat'))) { // Sidebar
	$category = get_category(get_query_var('cat'));
}	

if(get_query_var('diary_year')) { // Display page load strip
	$diary_year = get_term_by('name', get_query_var('diary_year'), 'category');
	$year = $diary_year;

	$month = '';
	$diary_month_posts = '';
}

get_header();
?>
<div id="content_left">
	<div class="years-navigation">
		<div class="years-navigation-carousel">
			<div class="frame">
				<ul class="years-navigation-list">
					<?php $y = 1; foreach ($years as $year_data): ?>
					<?php if(!$year_data->parent && $year_data->name != 'Sidebar' && !in_array($year_data->term_id, $hidden_years)) : // Exclude month sub categories,  sidebar & hidden years ?>
						<li <?= ($year_data->name == get_query_var('diary_year') ? 'class="year-active active"' : '') ?>>
							<a href="/the-diary/<?= $year_data->name ?>" class="get-months" data-year-id="<?= $year_data->term_id; ?>" data-year="<?= $year_data->name ?>"><?= $year_data->name ?></a>
						</li>
					<?php endif; ?>
					<?php $y++; endforeach ?>
				</ul>
			</div>
			<div class="scrollbar">
				<div class="handle">
					<div class="mousearea"></div>
				</div>
			</div>
			<div class="controls">
				<span class="prevPage">&#8249;</span>
				<span class="nextPage">&#8250;</span>
			</div>
		</div>
	</div>
	<div class="months-navigation group">
		<?php if(isset($diary_year)) : ?>
			<ul style="opacity: 1;">
				<?php $selected_month_empty = false; ?>
				<?php foreach(diary_get_months_for_year(['id' => $diary_year->term_id]) as $month_data) : ?>
					<?php
						if($month_data['id'] && $month_data['post_count'] > 0) {
							if($selected_month_empty) {
								// Use this month as the selected month as it's not empty
								echo '<script>window.location.href = "/the-diary/' . get_query_var('diary_year') . '/' . $month_data['month_shorthand'] . '";</script>';
							}

							// Display the strip for the month within URL
							if(get_query_var('diary_month') == $month_data['month_shorthand']) {
								$diary_month_posts = get_posts(['posts_per_page' => -1, 'category' => $month_data['id'], 'orderby' => 'menu_order', 'order' => 'ASC']);
								$month = get_term($month_data['id'], 'category');
							}

					 		echo '<li class="' . strtolower($month_data['month_shorthand']) . ' has-posts ' . (get_query_var('diary_month') == $month_data['month_shorthand'] ? 'active' : '') . '"><a href="/the-diary/' . $diary_year->name . '/' . $month_data['month_shorthand'] . '" class="get-month-strip" data-month-id="' . $month_data['id'] . '" data-month="' . $month_data['month_shorthand'] . '">' . $month_data['month_shorthand'] . '</a></li>';
						} else {
							// 
							if(get_query_var('diary_month') == $month_data['month_shorthand']) {
								$selected_month_empty = true;
							}

							echo '<li class="' . strtolower($month_data['month_shorthand']) . '">' . $month_data['month_shorthand'] . '</li>';
						}
					?>
				<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<ul></ul>
		<?php endif; ?>
	</div>
	<div class="diary-strip-container">
		<?php
			if(isset($diary_month_posts) && !empty($diary_month_posts)) { // Display month posts on page load based on query var
				$posts = $diary_month_posts;
				include get_template_directory() . '/template-parts/diary-strip.php';
			} elseif(isset($category) && isset($category->parent) && $category->parent == 259) { // Sidebar item
				$posts = get_posts(['posts_per_page' => -1, 'category' => $category->term_id, 'orderby' => 'menu_order', 'order' => 'ASC']);
				include get_template_directory() . '/template-parts/diary-strip.php';
			} else {
				echo '<div class="intro" style="display: block;">';
				echo get_field('introduction', 'option');
				echo '</div>';
			}
			?>
		</div>
	</div>
<?php get_sidebar(); ?>
<script>
	<?php if(get_query_var('diary_year')) : ?>
		activeYear = '<?= get_query_var('diary_year') ?>';
	<?php endif; ?>

	<?php if(get_query_var('diary_month')) : ?>
		activeMonth = '<?= get_query_var('diary_month') ?>';
	<?php endif; ?>
</script>
<?php get_footer(); ?>
