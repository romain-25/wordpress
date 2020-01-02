<?php
namespace Briefcase;

/**
 * Single Product Sale Flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/sale-flash.php.
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
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

// Variables come from product-image.php

// New
$postdate    = get_the_time( 'Y-m-d', $post->ID );
$timestamp   = strtotime( $postdate );
$newdays     = apply_filters( 'bew_shop_new_days', isset($new_days) );
$is_new      = ( time() - $timestamp < 60 * 60 * 24 * isset($newdays) ) && isset($shop_new_badge_on) ;
$is_featured = $product->is_featured() && isset($shop_hot_badge_on) ;
$is_sale     = $product->is_on_sale() && isset($shop_sale_badge_on);

if ( $is_new || $is_featured || $is_sale || ! $product->is_in_stock() ) {
	echo '<div class="bew-product-badges">';
}

if ( ! $product->is_in_stock() ) {
	echo '<span class="outofstock">' . esc_html__( $shop_outofstock_badge_text, 'briefcase-elementor-widgets' ) . '</span>';
}

if ( $is_new ) {
	echo '<span class="new">' . esc_html__( $shop_new_badge_text, 'briefcase-elementor-widgets' ) . '</span>';
}

// Sale
if ( $is_sale ) {

	$str = esc_html__( $shop_sale_badge_text, 'briefcase-elementor-widgets' );
	
	$helper = new Helper();

	if ( $shop_sale_percent_on && ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) ) {
		$str = $helper->get_sale_percentage( $product );
	}

	echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . $str . '</span>', $post, $product );
}

// Featured (Hot)
if ( $is_featured ) {
	echo '<span class="hot">' . esc_html__( $shop_hot_badge_text, 'briefcase-elementor-widgets' ) . '</span>';
}

if ( $is_new || $is_featured || $is_sale || ! $product->is_in_stock() ) {
	echo '</div>';
}
