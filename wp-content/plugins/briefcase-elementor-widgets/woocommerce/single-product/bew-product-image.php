<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.3.2
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if(Elementor\Plugin::instance()->editor->is_edit_mode()){							
global $product;

$temp_post = $GLOBALS['post'];
$GLOBALS['post'] = get_post($product->get_id());

} else {
global $post, $product;
}

$attachment_count  = count( $product->get_gallery_image_ids() );
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = get_post_thumbnail_id( $product->get_id() );
$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . $placeholder,
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	) );

// Product_gallery_layout come from the woo_dinamyc_field widget

// Product_gallery_zoom come from the woo_dinamyc_field widget

// Product_gallery_lightbox come from the woo_dinamyc_field widget

// Show only featured images
	$show_only_featured_images = false;

// Labels come from the woo_dinamyc_field widget

if ( $product_gallery_labels_new == 'yes') {
	$shop_new_badge_on = true;
}

if ( $product_gallery_labels_new_text == '') {
	$product_gallery_labels_new_text = 'New';
}

if ( $product_gallery_labels_new == 'yes') {
	$new_days = $product_gallery_labels_new_days;
}

if ( $product_gallery_labels_featured == 'yes') {
	$shop_hot_badge_on = true;
}

if ( $product_gallery_labels_featured_text == '') {
	$product_gallery_labels_featured_text = 'Hot';
}

if ( $product_gallery_labels_outofstock == 'yes') {
	$shop_outofstock_badge_on = true;
}

if ( $product_gallery_labels_outofstock_text == '') {
	$product_gallery_labels_outofstock_text = 'Out of stock';
}

if ( $product_gallery_labels_sale == 'yes') {
	$shop_sale_badge_on = true;
}

if ( $product_gallery_labels_sale_text == '') {
	$product_gallery_labels_sale_text = 'Sale';
}

if ( $product_gallery_labels_sale_percent == 'yes') {
	$shop_sale_percent_on = true;
}

if ( $product_gallery_layout == 'horizontal' || $product_gallery_layout == 'vertical' ) {
	$thumbnails_position = $product_gallery_layout;
}

$wrapper_classes[] = 'thumbnails-' . $thumbnails_position;

if ( $product_gallery_layout == 'sticky' ) {
	$product_gallery_zoom = false;
	$product_gallery_lightbox = false;
}

if ( $product_gallery_zoom == 'yes' ) {
	$wrapper_classes[] = 'product-zoom-on';
}


$attachment_ids    = $product->get_gallery_image_ids();
$carousel_settings = array();
$lightbox_btn_html = '';

?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>">
	<div
		class="bew-gallery-content<?php if ( $thumbnails_position == 'vertical' && $attachment_ids && ! $show_only_featured_images ) : ?> col-md-9<?php endif; ?>">
		<figure class="woocommerce-product-gallery__wrapper">
			<?php
			// Sale flash
			wc_get_template( 'single-product/bew-sale-flash.php',
			array(	'new_days' => $new_days, 
					'shop_new_badge_on' 		 => $shop_new_badge_on,
					'shop_new_badge_text'		 => $product_gallery_labels_new_text,
					'shop_hot_badge_on' 		 => $shop_hot_badge_on,
					'shop_hot_badge_text' 		 => $product_gallery_labels_featured_text,
					'shop_outofstock_badge_text' => $product_gallery_labels_outofstock_text,
					'shop_sale_badge_on' 		 => $shop_sale_badge_on,
					'shop_sale_badge_text' 	 	 => $product_gallery_labels_sale_text,
					'shop_sale_percent_on' 		 => $shop_sale_percent_on ) );

			$attributes = array(
				'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
				'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
				'data-src'                => $full_size_image[0],
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
			);

			if ( has_post_thumbnail() ) {

				$size = 'woocommerce_single';

				if ( $show_only_featured_images ) {
					$size = 'full';
				}

				// light box button
				if ( $product_gallery_lightbox == 'yes' ) {
					$lightbox_btn_html = '<a href="#" class="hint--left hint--bounce lightbox-btn" aria-label="' . esc_html__( 'Click to enlarge',
							'briefcase elementor widgets' ) . '">' . esc_html__( 'Click to enlarge',
							'briefcase elementor widgets' ) . '<i class="ion-android-expand"></i></a>';
				}

				if ( $product_gallery_layout == 'fullwidth' || $product_gallery_layout == 'sticky' ) {
					$cropping_width  = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '3' ) );
					$cropping_height = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '4' ) );
					$image_width     = intval( get_option( 'woocommerce_single_image_width', 540 ) ) * 1.25;
					$size            = array(
						$image_width,
						absint( round( ( $image_width / $cropping_width ) * $cropping_height ) ),
					);
				}

				$html = '<div data-thumb="' . get_the_post_thumbnail_url( $post->ID,
						'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
				$html .= get_the_post_thumbnail( $post->ID,
					apply_filters( 'bew_single_product_large_thumbnail_size', $size ),
					$attributes );
				$html .= '</a>';
				$html .= '</div>';
			} else {
				$html = '<div class="woocommerce-product-gallery__image--placeholder">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />',
					esc_url( wc_placeholder_img_src() ),
					esc_html__( 'Awaiting product image', 'briefcase elementor widgets' ) );
				$html .= '</div>';
			}

			$images         = array();
			$images[]       = apply_filters( 'woocommerce_single_product_image_thumbnail_html',
				$html,
				get_post_thumbnail_id( $post->ID ) );
			$attachment_ids = $product->get_gallery_image_ids();

			if ( $attachment_ids && has_post_thumbnail() && ! $show_only_featured_images ) {
				foreach ( $attachment_ids as $attachment_id ) {
					$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
					$attributes      = array(
						'title'                   => get_post_field( 'post_title', $attachment_id ),
						'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
						'data-src'                => $full_size_image[0],
						'data-large_image'        => $full_size_image[0],
						'data-large_image_width'  => $full_size_image[1],
						'data-large_image_height' => $full_size_image[2],
					);

					$html = '<div data-thumb="' . wp_get_attachment_image_src( $attachment_id,
							'shop_thumbnail' )[0] . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
					$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
					$html .= '</a></div>';

					$images[] = $html;
				}
				
				//option for wvs_pro plugin
				if ( class_exists( 'Woo_Variation_Swatches' ) ){
					$infinite = false; 
				}else{
					$infinite = count( $attachment_ids ) >= 3; 
				}
				
				$carousel_settings = apply_filters( 'bew_single_product_carousel_settings',
					array(
						'accessibility' => false,
						'infinite'      => $infinite,
						'arrows'        => true,
						'dots'          => true,
						'asNavFor'      => '.thumbnails-slider',
					) );
			}

			echo '<div class="woocommerce-product-gallery__slider"' . ( empty( $carousel_settings ) ? '' : ' data-carousel="' . esc_attr( json_encode( $carousel_settings ) ) . '"' ) . '>' . implode( "\n\t",
					$images ) . '</div>';

			echo $lightbox_btn_html;

			?>
		</figure>
	</div>
	<?php if ( $product_gallery_layout != 'sticky' && $product_gallery_layout != 'sticky-fullwidth') { ?>
		<div
			class="bew-thumbnails-content<?php if ( $thumbnails_position == 'vertical' && $attachment_ids ) : ?> col-md-3 flex-md-first<?php endif; ?>">
			<?php wc_get_template( 'single-product/bew-product-thumbnails.php', array('thumbnails_position' => $thumbnails_position) ); ?>
		</div>
	<?php } ?>
</div>
<?php
if(Elementor\Plugin::instance()->editor->is_edit_mode()){							
$GLOBALS['post'] = $temp_post;
}

