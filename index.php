<?php
get_header(); ?>
	<div class="zone1">
		<div class="content gallerywrap">
			<?php the_post(); ?>
				<?php the_content(); ?>
		</div><!-- .content -->
	</div><!-- #zone1 -->
<?php get_footer(); ?>