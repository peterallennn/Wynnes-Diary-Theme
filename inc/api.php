<?php
add_action('rest_api_init', function() {
	$base_route = 'diary';
	// {URL}/{$base_route}/get-months-for-year/{$id}
  	register_rest_route($base_route, 'get-months-for-year/(?P<id>\d+)', array(
    	'methods' => 'GET',
    	'callback' => 'diary_get_months_for_year',
  	));
  	// {URL}/{$base_route}/get-month-strip/{$id}
  	register_rest_route($base_route, 'get-months-strip/(?P<id>\d+)', array(
    	'methods' => 'GET',
    	'callback' => 'diary_get_month_strip',
  	));
});
function diary_get_months_for_year($data)
{
	$year_id = $data['id'];
	$year = get_term($year_id, 'category');
	$months = [];
	$hidden_months = [];

	if(!is_user_logged_in()) {
		// Allow admins to see the hidden months still to ensure the months 'live preview' functionality works
		$hidden_months = json_decode(get_option('wdeditor_hidden_months'), true);
	}

	for($m=1; $m<=12; ++$m) {
	    $month = date('F', mktime(0, 0, 0, $m, 1));
	    $month_term = get_term_by('slug', $month . '-' . $year->name, 'category');
	    $months[] = [
	    	'month' => $month,
	    	'month_shorthand' => date('M', mktime(0, 0, 0, $m, 1)),
	    	'post_count' => ($month_term && !in_array($month_term->term_id, $hidden_months) ? $month_term->count : 0),
	    	'id' => (!empty($month_term) ? $month_term->term_id : null)
	    ];
	}
	return $months;
}
function diary_get_month_strip($data)
{
	$month_id = $data['id'];
	$month = get_term($month_id, 'category');
	$year = get_term($month->parent, 'category');
	$posts = get_posts(['posts_per_page' => -1, 'category' => $month_id, 'orderby' => 'menu_order', 'order' => 'ASC']);
	$ajax = true;
	ob_start();
	include get_template_directory() . '/template-parts/diary-strip.php';
	$strip = ob_get_clean();
	return $strip;
}