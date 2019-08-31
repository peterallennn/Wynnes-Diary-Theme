<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Wynne\'s_Diary
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<?php if(isset($_GET['admin_preview'])) : ?>
		<style>
			body {
				background: none !important;
			}
			header {
				display: none !important;
			}
			#wpadminbar {
				display: none !important;
			}
			#content_left {
				left: 0 !important;
				float: none !important;
				margin: 0 auto !important;
			}
			#content_right {
				display: none !important;
			}
			#footer {
				display: none !important;
			}
			html {
				margin-top: 0 !important;
			}
			body.blog {
				overflow: hidden !important;
			}
		</style>
	<?php endif; ?>
</head>
<body <?php body_class(); ?>>
	<?php if(!is_front_page()) : ?>
		<header>
			<div id="logo">
				<h1>
					<a href="/the-diary"><span class="displayNone">Wynne's Diary</span></a>
				</h1>
			</div>
			<div id="search">
			        <form action="/" method="GET">
			            <input class="text" type="text" name="s" value="<?= isset($_GET['s']) ? $_GET['s'] : '' ?>" placeholder="Search The Diary...">
			            <input type="submit" value="Go">
			        </form>
			    </div>
		</header>
	<?php endif; ?>
