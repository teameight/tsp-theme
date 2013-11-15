<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
<?php the_post(); ?>
<?php if(get_field('two_columns') ) { 
    $gal = 0;
    while (has_sub_field('two_columns')): ?>
	<div class="<?php echo $gal % 2 ? 'splitzone' : 'zone1'; ?>">
		<?php if($gal % 2){ ?>
		<div class="splitleft"></div>
		<div class="splitright"></div>
        <?php } ?>
		<div class="content cf pdT">
            <div class="lbox cf">
                <?php the_sub_field('left'); ?>
			</div>
            <div class="faqwrap cf">
                <?php the_sub_field('right'); ?>
            </div>
		</div><!-- .content -->
	</div><!-- #zone- -->
    <?php  $gal++; endwhile; ?>
<?php } ?>
<?php get_footer(); ?>