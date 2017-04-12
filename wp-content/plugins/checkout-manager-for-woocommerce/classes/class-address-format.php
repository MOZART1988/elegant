<?php	
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_filter( 'woocommerce_order_formatted_billing_address', 'phoe_update_formatted_billing_address', 99, 2);
				
add_filter( 'woocommerce_order_formatted_shipping_address','phoe_update_formatted_shipping_address', 99, 2);

add_filter('woocommerce_formatted_address_replacements', 'phoe_formatted_address_replacements', 99, 2);

add_filter('woocommerce_my_account_my_address_formatted_address', 'phoe_my_account_address_formatted_address', 99, 3);

function phoe_update_formatted_billing_address( $address, $obj ){

	$billing_fields = get_option('_phoeniixx_custom_billing_fields' );

	$billing_fields_default_key = array( 'billing_first_name', 'billing_last_name', 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_country', 'billing_postcode', 'billing_phone', 'billing_email' , 'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_country', 'shipping_postcode' );
	
	if(!empty( $billing_fields ) && count($billing_fields) > 0 )
	{
		
		foreach( $billing_fields as $billing_field ) {
		
			$fieldname = $billing_field['fieldname'];
			
			if( !in_array($fieldname,$billing_fields_default_key) )	
			{
				
				$address[  $fieldname ] = get_post_meta( $obj->id, '_' . $fieldname, true );
				
			}
			else
			{
				
				$address[ str_replace( 'billing_', '', $fieldname ) ] = get_post_meta( $obj->id, '_' . $fieldname, true );
				
			}
			
			
		}
	
	}
	

	return $address;    
}

function phoe_update_formatted_shipping_address( $address, $obj ){

	$shipping_fields = get_option('_phoeniixx_custom_shipping_fields' );

	$shipping_fields_default_key = array( 'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_country', 'shipping_postcode' );
	
	if(!empty( $shipping_fields ) && count($shipping_fields) > 0 )
	{
		
		foreach( $shipping_fields as $shipping_field ) {
			
			$fieldname = $shipping_field['fieldname'];

			if( !in_array($fieldname,$shipping_fields_default_key) )	
			{
				
				$address[  $fieldname ] = get_post_meta( $obj->id, '_' . $fieldname, true );
				
			}
			else
			{
				
				$address[ str_replace( 'shipping_', '', $fieldname ) ] = get_post_meta( $obj->id, '_' . $fieldname, true );
				
			}

		}
	
	}
	
	return $address;    
}

function phoe_formatted_address_replacements( $address, $args ){
					
	$custom_fields = array();

	$billing_fields = get_option('_phoeniixx_custom_billing_fields' );
	
	$shipping_fields = get_option('_phoeniixx_custom_shipping_fields' );
	
	$billing_fields_default_key = array( 'billing_first_name', 'billing_last_name', 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_country', 'billing_postcode', 'billing_phone', 'billing_email' , 'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_country', 'shipping_postcode' );
	
	$shipping_fields_default_key = array( 'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_country', 'shipping_postcode' );
	
	if(!empty( $billing_fields ) && count($billing_fields) > 0 )
	{
	
		foreach( $billing_fields as $billing_field ) {
			
			$fieldname = $billing_field['fieldname'];
			
			if( !in_array($fieldname,$billing_fields_default_key) )	
			{
				
				$custom_fields[] = $fieldname;
				
			}

		}
		
	}
	
	if(!empty( $shipping_fields ) && count( $shipping_fields ) > 0 )
	{
	
		foreach( $shipping_fields as $shipping_field ) {
			
			$fieldname = $shipping_field['fieldname'];
			
			if( !in_array($fieldname,$shipping_fields_default_key) )	
			{
				
				$custom_fields[] =  $fieldname;
				
			}

		}
	
	}
	
	
	if( empty( $custom_fields ) ) {
		return $address;
	}
	
	
	$text2 = '';
	
	foreach ( $custom_fields as $value ) {
		
		$text2 .= "\n".$args[$value];
	}
		
		
	
	$address['{state}'] = $args['state'].$text2;
	
	return $address;
}

function phoe_my_account_address_formatted_address( $address, $customer_id, $name ){
	
	$billing_fields = get_option('_phoeniixx_custom_billing_fields' );
		
	$shipping_fields = get_option('_phoeniixx_custom_shipping_fields' );
	
	$billing_fields_default_key = array( 'billing_first_name', 'billing_last_name', 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_country', 'billing_postcode', 'billing_phone', 'billing_email' , 'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_country', 'shipping_postcode' );
	
	$shipping_fields_default_key = array( 'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_country', 'shipping_postcode' );

	if(!empty( $billing_fields ) && count($billing_fields) > 0 ) {
		
		if(is_array($billing_fields)){
			
			foreach($billing_fields as $billing_field){
				
				$fieldname = $billing_field['fieldname'];
				
				if( !in_array($fieldname,$billing_fields_default_key) )	
				{
					
					$custom_fields_billing[] = $fieldname;
					
				}

			}
		}
	
	}
	
	if(!empty( $shipping_fields ) && count($shipping_fields) > 0 )
	{
	
		if(is_array($shipping_fields)){
			
			foreach($shipping_fields as $shipping_field){
				
				$fieldname = $shipping_field['fieldname'];
				
				if( !in_array($fieldname,$shipping_fields_default_key) )	
				{
					
					$custom_fields_shipping[] = $fieldname;
					
				}

			}
		}
	}
	
	
	if( empty( $custom_fields_billing ) && empty( $custom_fields_shipping ) ) {
		
		return $address;
	}
	
	foreach($custom_fields_billing as $custom_field_billing){
		
		if($name == 'billing')
		{
			
			$address[$custom_field_billing] = get_user_meta( $customer_id, $custom_field_billing, true );
			
		}

	}
	
	foreach($custom_fields_shipping as $custom_field_shipping){
		
		if($name == 'shipping')
		{
			
			$address[$custom_field_shipping] = get_user_meta( $customer_id, $custom_field_shipping, true );
			
		}
		
	}

	return $address;
}