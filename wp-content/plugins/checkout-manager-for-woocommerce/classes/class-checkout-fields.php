<?php	
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_filter( 'woocommerce_billing_fields', 'phoe_checkout_custom_billing_fields' );

add_filter( 'woocommerce_shipping_fields', 'phoe_checkout_custom_shipping_fields' );

add_filter( 'woocommerce_checkout_fields', 'phoe_checkout_custom_additional_fields' );	

function phoe_checkout_custom_billing_fields( $default_billing_fields ) {

	$billing_fields = get_option('_phoeniixx_custom_billing_fields' );
	
	/* echo "<pre>";
	
	print_r( $billing_fields );
	
	echo "</pre>"; */
	
	$billing_fields_default_key = array( 'billing_first_name', 'billing_last_name', 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_country', 'billing_postcode', 'billing_phone', 'billing_email' );
	
	if( empty($billing_fields) )
	{
		
		$billing_fields = $default_billing_fields;
		
	}
	else 
	{
		
		foreach( $billing_fields as $name => $values ) {
			
			if ( $values['enabled'] == false ) {
				
				unset( $billing_fields[ $name ] );
				
			}

			
			if ( in_array( $name, $billing_fields_default_key ) ) 
			{
				
				if ( isset( $billing_fields[ $name ] ) ) 
				{
					
					$billing_fields[ $name ]['label'] = ! empty( $billing_fields[ $name ]['label'] ) ? $billing_fields[ $name ]['label'] : $default_billing_fields[ $name ]['label'];
					
					if ( ! empty( $default_billing_fields[ $name ]['placeholder'] ) && empty( $billing_fields[ $name ]['placeholder'] )) 
					{
						
						$billing_fields[ $name ]['placeholder'] = $default_billing_fields[ $name ]['placeholder'];

					}
					
				}
				
			}
			
			
		}
		

	}
	
	return $billing_fields;

}


function phoe_checkout_custom_shipping_fields( $default_shipping_fields ) {

	$shipping_fields = get_option('_phoeniixx_custom_shipping_fields' );
	
	/* echo "<pre>";
	
	print_r( $shipping_fields );
	
	echo "</pre>"; */
	
	$shipping_fields_default_key = array( 'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_country', 'shipping_postcode' );

	
	if( empty($shipping_fields) )
	{
		
		$shipping_fields = $default_shipping_fields;
		
	}
	else 
	{
		
		foreach( $shipping_fields as $name => $values ) {
			
			if ( $values['enabled'] == false ) {
				
				unset( $shipping_fields[ $name ] );
				
			}

			
			if ( in_array( $name, $shipping_fields_default_key ) ) 
			{
				
				if ( isset( $shipping_fields[ $name ] ) ) 
				{
					
					$shipping_fields[ $name ]['label'] = ! empty( $shipping_fields[ $name ]['label'] ) ? $shipping_fields[ $name ]['label'] : $default_shipping_fields[ $name ]['label'];
					
					if ( ! empty( $default_shipping_fields[ $name ]['placeholder'] ) && empty( $shipping_fields[ $name ]['placeholder'] )) 
					{
						
						$shipping_fields[ $name ]['placeholder'] = $default_shipping_fields[ $name ]['placeholder'];

					}
					
				}
			}
			
			
		}
		

	}
	
	return $shipping_fields;

}

function phoe_checkout_custom_additional_fields( $default_additional_fields )
{
	
	/* echo "<pre>";
	print_r($default_additional_fields);
	echo "<pre>"; */
	
	$additional_fields = get_option('_phoeniixx_custom_additional_fields' );
	
	$additional_fields_default_key = array( 'order_comments' );

	
	if( empty($additional_fields) )
	{
		
		$additional_fields = $default_additional_fields['order'];
		
	}
	else 
	{
		
		foreach( $additional_fields as $name => $values ) {
			
			if ( $values['enabled'] == false ) {
				
				unset( $additional_fields[ $name ] );
				
			}

			
			if ( in_array( $name, $additional_fields_default_key ) ) 
			{
				
				if ( isset( $additional_fields[ $name ] ) ) 
				{
					
					$additional_fields[ $name ]['label'] = ! empty( $additional_fields[ $name ]['label'] ) ? $additional_fields[ $name ]['label'] : $default_additional_fields[ $name ]['label'];
					
					if ( ! empty( $default_additional_fields[ $name ]['placeholder'] ) && empty( $additional_fields[ $name ]['placeholder'] )) 
					{
						
						$additional_fields[ $name ]['placeholder'] = $default_additional_fields[ $name ]['placeholder'];

					}
					
				}
			}
			
			
		}
		

	}
	
	
	
	$default_additional_fields['order'] = $additional_fields;
	
	/* echo "<pre>";
	print_r($default_additional_fields['order']);
	echo "<pre>"; */
	
	return $default_additional_fields;
	
	
}
			
?>