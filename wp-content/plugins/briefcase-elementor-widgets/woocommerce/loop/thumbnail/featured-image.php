<?php
/**
 * Image Featured style thumbnail
 *
 * @package BriefcaseWP
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Display featured image if defined
?>

	<div class="woo-entry-image bew-featured-image clr">
		<a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link">
			<?php			
			// Single Image
			echo woocommerce_get_product_thumbnail( $size = isset($product_image_size) ? $product_image_size : 'woocommerce_thumbnail' ); ?>
	    </a>
		
		<?php		
		if (wp_get_theme(get_template()) == 'OceanWP') {
		do_action( 'ocean_after_product_entry_image' );	
		}
		?>
	</div><!-- .woo-entry-image -->

<?php

