<?php
/**
 * Wynne\'s Diary functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Wynne\'s_Diary
 */
if ( ! function_exists( 'wynnes_diary_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function wynnes_diary_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Wynne\'s Diary, use a find and replace
		 * to change 'wynnes-diary' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'wynnes-diary', get_template_directory() . '/languages' );
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'wynnes-diary' ),
		) );
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		add_theme_support( 'post-formats', array(
	    	'audio',
	    	'image',
	    	'link',
	    	'quote',
	    	'video',
	    ) );
		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'wynnes_diary_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
		// Add Advanced Custom Field's options page
		if( function_exists('acf_add_options_page') ) {
			acf_add_options_page([
				'page_title' => 'Global Content'
			]);
		}
	}
endif;
add_action( 'after_setup_theme', 'wynnes_diary_setup' );
/** 
 * Add the page slug the <body> class
 */
function add_slug_body_class( $classes ) {
	global $post;
	if (isset($post)) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wynnes_diary_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'wynnes_diary_content_width', 640 );
}
add_action( 'after_setup_theme', 'wynnes_diary_content_width', 0 );
/**
 * Allow HTML in term (category, tag) descriptions 
         * Note : You may want to restrict access to who can edit category description or of the sort.
         * Removing the filter will enable anyone to insert html/javascript code (which may be malicious)
 */
foreach ( array( 'pre_term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_filter_kses' );
}
foreach ( array( 'term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_kses_data' );
}
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wynnes_diary_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'wynnes-diary' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'wynnes-diary' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'wynnes_diary_widgets_init' );
/**
 * Enqueue scripts and styles.
 */
function wynnes_diary_scripts() {
	wp_enqueue_style( 'wynnes-diary-style', get_stylesheet_uri() );
	wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js', false, null, true );
    wp_enqueue_script( 'jquery' );
	wp_register_script( 'jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.0.1/jquery-migrate.min.js', array('jquery'), null, true );    
	wp_enqueue_script( 'wynnes-diary-plugins', get_template_directory_uri() . '/js/plugins.js', array('jquery'), null, true );
	wp_enqueue_script( 'wynnes-diary-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), null, true );	
	wp_enqueue_script( 'wynnes-diary-diary', get_template_directory_uri() . '/js/diary.js', array('jquery'), null, true );	
}
add_action( 'wp_enqueue_scripts', 'wynnes_diary_scripts' );
/**
 * Pagination links for search and archives
 */
function get_pagination_links() {
    global $wp_query;
    $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
    $big = 999999999;
    return paginate_links( array(
        'base' => @add_query_arg('paged','%#%'),
        'format' => '?paged=%#%',
        'current' => $current,
        'total' => $wp_query->max_num_pages,
        'prev_next'    => false
    ) );
}

function custom_rewrite_tag() {
  add_rewrite_tag('%diary_year%', '([^&]+)');
  add_rewrite_tag('%diary_month%', '([^&]+)');
}
add_action('init', 'custom_rewrite_tag', 10, 0);

function custom_rewrite_rule() {
	add_rewrite_rule('^the-diary/([^/]*)/([^/]*)/?','index.php?diary_year=$matches[1]&diary_month=$matches[2]', 'top');
	add_rewrite_rule('^the-diary/([^/]*)/?','index.php?diary_year=$matches[1]', 'top');
}
add_action('init', 'custom_rewrite_rule', 10, 0);

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
/**
 * Add modification to 'term.php' form
 */
require get_template_directory() . '/inc/admin/category-form.php';
/**
 * Register custom API endpoints
 */
require get_template_directory() . '/inc/api.php';