<?php
/**
 * Gallery Style WooCommerce
 *
 * @package BriefcaseWP
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(Elementor\Plugin::instance()->editor->is_edit_mode()){							
// Globals
global $post, $product;

// check post id and briefcase type template

$bew_template_type = get_post_meta($post->ID, 'briefcase_template_layout', true);

// Get the product data 
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
$thumbnail_id  = get_post_thumbnail_id();

// Get gallery images
if ( version_compare( WC()->version, '2.7', '>=' ) ) {	
	$attachment_ids   = $product->get_gallery_image_ids();
} else {
	$attachment_ids   = $product->get_gallery_attachment_ids();
}

// Get attachments count
$attachments_count = count( $attachment_ids );

// Image args
$img_args = array(
    'alt' => get_the_title(),
);

$img_args['itemprop'] = 'image';

if ( $product_image_slider_layout == 'horizontal' || $product_image_slider_layout == 'vertical' ) {
	$thumbnails_position = $product_image_slider_layout;
}

$wrapper_classes = 'thumbnails-' . $thumbnails_position;


// If there are attachments display slider
if ( $attachment_ids ) : ?>

	<div class="bew-slider-image <?php echo $wrapper_classes; ?>">

		<?php do_action( 'bew_before_product_entry_slider' ); ?>

		<div class="bew-product-entry-slider woo-entry-image clr">

			<?php do_action( 'bew_before_product_entry_image' ); ?>

			<?php
			// Define counter variable
			$count=0;

			if ( has_post_thumbnail() ) : ?>

				<div class="bew-slider-slide bew-current">
					<a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link">
						<?php
						echo wp_get_attachment_image( $thumbnail_id, $product_image_size, '', $img_args ); ?>
				    </a>
				</div>

			<?php
			endif;

			if ( $attachments_count > 0 ) :

				// Loop through images
				foreach ( $attachment_ids as $attachment_id ) :

					// Add to counter
					$count++;

					// Only display the first 5 images
					if ( $count < 5 ) : ?>

						<div class="bew-slider-slide">
							<a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link">
								<?php
								echo wp_get_attachment_image( $attachment_id, $product_image_size, '', $img_args ); ?>
						    </a>
						</div>

					<?php
					endif;

				endforeach;

			endif; ?>

			<?php do_action( 'bew_after_product_entry_image' );				 
			?>

		</div>
		
		<?php
		
		if ( $slider_thumbnails && (is_shop() || (Elementor\Plugin::instance()->editor->is_edit_mode() && 'woo-shop' == $bew_template_type)) ) :
		?>
			<div class="bew-thumbnails-content<?php if ( $thumbnails_position == 'vertical' && $attachment_ids ) : ?> col-md-3 flex-md-first<?php endif; ?>">
			<?php wc_get_template( 'loop/bew-archive-product-thumbnails.php', array('thumbnails_position' => $thumbnails_position) ); ?>
			</div>
		<?php
		endif;
		?>
		<?php do_action( 'bew_after_product_entry_slider' ); 
			//  if (wp_get_theme(get_template()) == 'OceanWP') {
			//	global $product;
			//	$button  = '<a href="#" id="product_id_' . $product->get_id() . '" class="owp-quick-view" data-product_id="' . $product->get_id() . '"><i class="icon-eye"></i>' . esc_html__( 'Quick View', 'oceanwp' ) . '</a>';
			//	echo apply_filters( 'ocean_woo_quick_view_button_html', $button );	
			//  } 
		?>
		

	</div>

<?php
// There aren't any images so lets display the featured image
else :

	wc_get_template( 'loop/thumbnail/featured-image.php' ,
							array('product_image_size' => $product_image_size ) );

endif;

if(Elementor\Plugin::instance()->editor->is_edit_mode()){							
$GLOBALS['post'] = $temp_post;
}

 ?>