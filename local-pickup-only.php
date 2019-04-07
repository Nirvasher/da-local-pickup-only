<?php
/*
Plugin Name: Local Pickup Only
Description: This is a simple plugin for WooCommerce, that let's you set products to be only picked up from the store.
Version: 1.0
Author: Daniel Andersen
Author URI: http://dnest.se/
*/

function da_only_local_pickup($rates) {
    global $woocommerce;
 
    // Change slug to what you name your weight class for the product.
    $slug = 'hamta-endast-i-butik';
    $product_in_cart = false;
 
    foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
 
        $_product = $values['data'];
        $terms = get_the_terms( $_product->id, 'product_shipping_class' );
 
        if ( $terms ) {
            foreach ( $terms as $term ) {
                $_shippingclass = $term->slug;
                if ( $slug === $_shippingclass ) {
                     
                    $product_in_cart = true;
                }
            }
        }
    }
    
    $newRates = array();
    if ($product_in_cart) {
        foreach ( $rates as $id => $rate ) {
            if ($rate->method_id == 'local_pickup') {
                $newRates[$id] = $rate;
            }
        }
    }

    return empty($newRates) ? $rates : $newRates;
}

add_filter('woocommerce_package_rates', 'da_only_local_pickup', 10, 2 );