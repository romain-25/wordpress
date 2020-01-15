<?php
/*
  Template Name: Services
    */
	get_header();
	if ( have_posts() ) : while ( have_posts() ) : the_post();
	the_post_thumbnail( 'square' );
?>
	<h1><?php the_title(); ?></h1>

    <div class="content">
    	<?php the_content(); ?>
    </div>

<?php
endwhile; endif;
get_footer();
?>