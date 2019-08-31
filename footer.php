<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Wynne\'s_Diary
 */

?>

<div id="footer">
	<?= get_field('footer_content', 'option') ?>
</div>

<?php wp_footer(); ?>

</body>
</html>
