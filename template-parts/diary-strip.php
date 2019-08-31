<div class="diary-strip group">
	<div class="diary-strip-post month-description" <?= (!isset($ajax) ? 'style="opacity:1;"' : '') ?>>
		<div class="post-header"></div>
		<div class="post-content">
			<?php if(isset($year->name) && isset($month->name)) : ?>
				<h2><?= $year->name ?></h2>
				<h3>The Diary in <?= $month->name ?></h3>
				<?= $month->description ?>
			<?php endif; ?>
			<?php if(isset($category) && !is_wp_error($category)) : ?>
				<?= $category->description ?>
			<?php endif; ?>
		</div>
		<div class="post-footer">
			<span class="blank"></span>
		</div>
	</div>
	<?php foreach ($posts as $post): ?>
		<div class="diary-strip-post" <?= (!isset($ajax) ? 'style="opacity:1;"' : '') ?>>
			<div class="post-header"></div>
			<div class="post-content">
				<?php if(has_post_thumbnail($post->ID)) : ?>
					<a href="<?= get_permalink($post->ID) ?>" class="featured-image"><img src="<?= get_the_post_thumbnail_url($post->ID) ?>" alt=""></a>
					<?php if(!empty($post->post_excerpt)) : ?>
						<p><strong><?= apply_filters('the_excerpt', $post->post_excerpt) ?></strong></p>
					<?php endif; ?>
				<?php else : ?>
					<?php if(isset($category) && $category->parent == 259) : ?>
						<p><strong><?= $post->post_title; ?></strong></p>
					<?php endif; ?>
					<p><?= apply_filters('the_excerpt', preg_replace('/<p[^>]*><\\/p[^>]*>/', '', $post->post_excerpt)) ?></p>
				<?php endif; ?>
			</div>
			<div class="post-footer">
				<?php if(has_post_thumbnail($post->ID)) : ?>
					<a href="<?= get_permalink($post->ID) ?>" class="view-bigger"></a>
				<?php else : ?>
					<a href="<?= get_permalink($post->ID) ?>" class="more-info"></a>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach ?>
</div>