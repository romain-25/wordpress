<?php
/**
 * Image Swap style thumbnail
 *
 * @package BriefcaseWp
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if(Elementor\Plugin::instance()->editor->is_edit_mode()){							
// Globals
global $product;

$temp_post = $GLOBALS['post'];
$GLOBALS['post'] = get_post($product->get_id());

} else {
// Globals	
global $post, $product;
}

// Return dummy image if no featured image is defined
if ( ! has_post_thumbnail() ) {
	esc_url( wc_placeholder_img_src() );
	return;
}

// Get first image
$attachment = get_post_thumbnail_id();

// Get Second Image in Gallery
if ( version_compare( WC()->version, '2.7', '>=' ) ) {	
	$attachment_ids   = $product->get_gallery_image_ids();
} else {
	$attachment_ids   = $product->get_gallery_attachment_ids();
}
$attachment_ids[] = $attachment; // Add featured image to the array
$secondary_img_id = '';

if ( ! empty( $attachment_ids ) ) {
	$attachment_ids = array_unique( $attachment_ids ); // remove duplicate images
	if ( count( $attachment_ids ) > '1' ) {
		if ( $attachment_ids['0'] !== $attachment ) {
			$secondary_img_id = $attachment_ids['0'];
		} elseif ( $attachment_ids['1'] !== $attachment ) {
			$secondary_img_id = $attachment_ids['1'];
		}
	}
}

// Image args
$first_img = array(
    'class'         => 'woo-entry-image-main',
    'alt'           => get_the_title(),
);

	$first_img['itemprop'] = 'image';


$second_img = array(
    'class'         => 'woo-entry-image-secondary',
    'alt'           => get_the_title(),
);

	$second_img['itemprop'] = 'image';



// Return thumbnail
if ( $secondary_img_id ) : ?>

	<div class="woo-entry-image bew-swap-image clr">
		<?php do_action( 'bew_before_product_entry_image' ); ?>
		<a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link">
			<?php
			// Main Image
			echo wp_get_attachment_image( $attachment, $product_image_size, '', $first_img ); ?>
			<?php
			// Secondary Image
			echo wp_get_attachment_image( $secondary_img_id, $product_image_size, '', $second_img ); ?>
		</a>
		<?php do_action( 'bew_after_product_entry_image' ); 
		if (wp_get_theme(get_template()) == 'OceanWP') {
		do_action( 'ocean_after_product_entry_image' );	
		} 
		?>
	</div><!-- .woo-entry-image-swap -->

<?php else : ?>

	<div class="woo-entry-image clr">
		<?php do_action( 'bew_before_product_entry_image' ); ?>
		<a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link">
			<?php
			// Single Image
			echo wp_get_attachment_image( $attachment, $product_image_size, '', $first_img ); ?>
		</a>
		<?php do_action( 'bew_after_product_entry_image' ); 
		if (wp_get_theme(get_template()) == 'OceanWP') {
		do_action( 'ocean_after_product_entry_image' );	
		} 
		?>
	</div><!-- .woo-entry-image -->

<?php endif; 
if(Elementor\Plugin::instance()->editor->is_edit_mode()){							
$GLOBALS['post'] = $temp_post;
}
?>