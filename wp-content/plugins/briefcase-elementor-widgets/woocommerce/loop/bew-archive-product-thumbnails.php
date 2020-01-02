<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
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

global $product, $woocommerce;

$temp_post = $GLOBALS['post'];
$GLOBALS['post'] = get_post($product->get_id());

// Product gallery thumbnails_position come from product-image.php

$attachment_ids = $product->get_gallery_image_ids();

$carousel_settings = apply_filters( 'bew_single_product_thumbnails_carousel_settings',
	array(
		'accessibility' => false,
		'focusOnSelect' => true,
		'variableWidth' => true,
		'slidesToShow'  => 3,
		'infinite'      => count( $attachment_ids ) >= 3,		
	) );

if ( $thumbnails_position == 'vertical' ) {
	$carousel_settings['slidesToShow']    = 3;
	$carousel_settings['vertical']        = true;
	$carousel_settings['verticalSwiping'] = true;
	$carousel_settings['adaptiveHeight']  = true;
	$carousel_settings['variableWidth']   = false;
}

if ( $attachment_ids && has_post_thumbnail() ) { ?>
	<div class="thumbnails bew-thumbnails-slider"
	     data-carousel="<?php echo esc_attr( json_encode( $carousel_settings ) ) ?>"><?php

			$main_attachment_id = get_post_thumbnail_id(get_the_ID());
			$single_image       = wp_get_attachment_image_src( $main_attachment_id, 'woocommerce_single' );
			$full_size_image    = wp_get_attachment_image_src( $main_attachment_id, 'full' );
			$placeholder        = has_post_thumbnail() ? 'with-images' : 'without-images';

			$attributes = array(
				'title'                   => get_post_field( 'post_title', $main_attachment_id ),
				'data-caption'            => get_post_field( 'post_excerpt', $main_attachment_id ),
				'data-src'                => $full_size_image[0],
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
			);

			$gallery_thumbnail_size  = wc_get_image_size( 'gallery_thumbnail' );
			$woocommerce_single_size = wc_get_image_size( 'woocommerce_single' );
			$cropping_width          = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '3' ) );
			$cropping_height         = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '4' ) );

			$size = array(
				$gallery_thumbnail_size['width'],
				absint( round( ( $gallery_thumbnail_size['width'] / $cropping_width ) * $cropping_height ) ),
			);
			
			$html = '<a ?href="' . esc_url( $full_size_image[0] ) . '">';
			$html .= get_the_post_thumbnail(get_the_ID(), apply_filters( 'bew_single_product_large_thumbnail_size', $size ),
				$attributes );
			$html .= '</a>';

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html',
				$html,
				get_post_thumbnail_id(get_the_ID()) );
		

		foreach ( $attachment_ids as $attachment_id ) {

			$size = array(
				$gallery_thumbnail_size['width'],
				absint( round( ( $gallery_thumbnail_size['width'] / $cropping_width ) * $cropping_height ) ),
			);


			$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
			$single_image    = wp_get_attachment_image_src( $attachment_id, 'woocommerce_single' );
			$thumbnail       = wp_get_attachment_image_src( $attachment_id, $size );
			$image_single    = wp_get_attachment_image_src( $attachment_id,
				apply_filters( 'bew_single_product_large_thumbnail_size', 'woocommerce_single' ) );

			$attributes = array(
				'title'                   => get_post_field( 'post_title', $attachment_id ),
				'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
				'data-src'                => $full_size_image[0],
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
			);

			$html = '<a ?href="' . esc_url( $full_size_image[0] ) . '">';
			$html .= wp_get_attachment_image( $attachment_id,
				apply_filters( 'bew_single_product_small_thumbnail_size', $size ),
				false,
				$attributes );
			$html .= '</a>';

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
		}

		?></div>
	<?php
}
$GLOBALS['post'] = $temp_post;
