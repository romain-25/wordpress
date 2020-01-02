<?php
/**
 * Product loop sale flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/sale-flash.php.
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
 * @version       1.6.4
 */
namespace Briefcase;
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$shop_new_badge_on = false;
$shop_hot_badge_on = false;
$shop_outofstock_badge_on = false;
$shop_sale_badge_on = false;
$shop_sale_percent_on = false;

global $post, $product;

// Labels come from the woo_dinamyc_field widget

if ((isset($product_image_labels_new) ? $product_image_labels_new : null) === 'yes') { 
	$shop_new_badge_on = true;
	$new_days = $product_image_labels_new_days;
}

if ((isset($product_image_labels_new_text) ? $product_image_labels_new_text : null) === '') { 
	$product_image_labels_new_text = 'New';
}

if ((isset($product_image_labels_featured) ? $product_image_labels_featured : null) === 'yes') { 
	$shop_hot_badge_on = true;
}

if ((isset($product_image_labels_featured_text) ? $product_image_labels_featured_text : null) === '') { 
	$product_image_labels_featured_text = 'Hot';
}

if ((isset($product_image_labels_outofstock) ? $product_image_labels_outofstock : null) === 'yes') { 
	$shop_outofstock_badge_on = true;
}

if ((isset($product_image_labels_outofstock_text) ? $product_image_labels_outofstock_text : null) === '') { 
	$product_image_labels_outofstock_text = 'Out of stock';
}

if ((isset($product_image_labels_sale) ? $product_image_labels_sale : null) === 'yes') { 
	$shop_sale_badge_on = true;
}

if ((isset($product_image_labels_sale_text) ? $product_image_labels_sale_text : null) === '') { 
	$product_image_labels_sale_text = 'Sale';
}

if ((isset($product_image_labels_sale_percent) ? $product_image_labels_sale_percent : null) === 'yes') { 
	$shop_sale_percent_on = true;
}

// New
$postdate    = get_the_time( 'Y-m-d', $post->ID );
$timestamp   = strtotime( $postdate );
$newdays     = apply_filters( 'bew_shop_new_days', isset($new_days) );
$is_new      = ( time() - $timestamp < 60 * 60 * 24 * $newdays ) && $shop_new_badge_on;
$is_featured = $product->is_featured() && $shop_hot_badge_on;
$is_sale     = $product->is_on_sale() && $shop_sale_badge_on;
$is_outstock = ! $product->is_in_stock() && $shop_outofstock_badge_on;

if ( ! $product->is_in_stock() || $is_new || $is_featured || $is_sale ) {
	echo '<div class="bew-product-badges">';
}
// Out of stock
if ( $is_outstock ) {
	echo '<span class="outofstock">' . esc_html__( $product_image_labels_outofstock_text, 'briefcase-elementor-widgets' ) . '</span>';
}

// New
if ( $is_new ) {
	echo '<span class="new">' . esc_html__( $product_image_labels_new_text, 'briefcase-elementor-widgets' ) . '</span>';
}

// Sale
if ( $is_sale ) {

	$str = esc_html__( $product_image_labels_sale_text, 'briefcase-elementor-widgets' );
	
	$helper = new Helper();

	if ( $shop_sale_percent_on && ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) ) {
		$str = $helper->get_sale_percentage( $product );
	}

	echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . $str . '</span>', $post, $product );
}

// Featured (Hot)
if ( $is_featured ) {
	echo '<span class="hot">' . esc_html__( $product_image_labels_featured_text, 'briefcase-elementor-widgets' ) . '</span>';
}

if ( ! $product->is_in_stock() || $is_new || $is_featured || $is_sale ) {
	echo '</div>';
}

