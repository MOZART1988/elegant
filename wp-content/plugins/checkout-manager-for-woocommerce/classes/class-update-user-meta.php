<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'woocommerce_checkout_update_order_meta', 'phoe_checkout_custom_additional_fields_meta' );	
				
add_action('woocommerce_checkout_update_user_meta', 'phoe_custom_checkout_field_update_user_meta' );

function phoe_checkout_custom_additional_fields_meta( $order_id ) {

	$additional_fields = get_option('_phoeniixx_custom_additional_fields' );
	
	$additional_fields_default_key = array( 'order_comments' );

	foreach ( $additional_fields as $name => $values ) {
		
		if ( !in_array( $name, $additional_fields_default_key ) ) 
		{
			
			if( !empty( $_POST[$name] ) ){
			
				update_post_meta( $order_id, $name, esc_attr($_POST[$name]) );
				
			}
			
		}

	}

}

function phoe_custom_checkout_field_update_user_meta( $user_id ) {
				
	$additional_fields = get_option('_phoeniixx_custom_additional_fields' );
	
	$additional_fields_default_key = array( 'order_comments' );
	
	foreach ( $additional_fields as $name => $values ) {
		
		if ( !in_array( $name, $additional_fields_default_key ) ) 
		{
			
			if( !empty( $_POST[$name] ) ){
				
				update_user_meta( $user_id, $name, esc_attr($_POST[$name]) );
				
			}
			
		}
		
	}

	
}
			
			