<?php	
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_filter('woocommerce_email_after_order_table', 'phoe_custom_email_after_order_table' ,30 ,3 );

add_action ('woocommerce_order_details_after_order_table', 'phoe_order_details_after_order_table' );

function phoe_custom_email_after_order_table( $order, $sent_to_admin, $plain_text ) {
	
	$fields = array();
	
	$additional_fields = get_option('_phoeniixx_custom_additional_fields' );
	
	$additional_fields_default_key = array( 'order_comments' );

	$user_id = $order->customer_user;

	unset($additional_fields['order_comments']);

	if( count($additional_fields) > 0 && !empty($additional_fields) )
	{
		
		foreach ( $additional_fields as $name => $values ) {
			
			if ( !in_array( $name, $additional_fields_default_key ) ) 
			{

				$value = get_post_meta( $order->id, $name ,true);
				
				if( ! empty( $user_id ) && ( $value != '' ) ) {
					
					$fields[$name] = array(
						'label' => $values['label'],
						'value' => $value
					);

				}

			}

		}
	
		if( $plain_text ) {

			woocommerce_get_template( 'email-customer-details.php', array( 'fields' => $fields ), 'phoe_checkout_manager', phoe_checkout_manager_path  . 'templates/emails/plain/' );

		}
		else
		{
			
			woocommerce_get_template( 'email-customer-details.php', array( 'fields' => $fields ), 'phoe_checkout_manager', phoe_checkout_manager_path  . 'templates/emails/' );
			
		}
	
	}

}

function phoe_order_details_after_order_table( $order ) {
	
	$additional_fields = get_option('_phoeniixx_custom_additional_fields' );
	
	$additional_fields_default_key = array( 'order_comments' );
	
	unset($additional_fields['order_comments']);

	if( count($additional_fields) > 0 && !empty($additional_fields) )
	{
		
		?>
		
			<header><h2>Additional Details</h2></header>
		
			<table class="shop_table additional_details">

				<tbody>
				
					<?php
					
					foreach ( $additional_fields as $name => $values ) {
						
						if ( !in_array( $name, $additional_fields_default_key ) ) 
						{
							
							$value = get_post_meta( $order->id, $name ,true);
							
							?>
							
								<tr>
								
									<th><?php echo $values['label']; ?>:</th>
									
									<td><?php echo $value; ?></td>
									
								</tr>
							
							<?php

						}
						
					}

					?>
		
				</tbody>
			
			</table>
		
		<?php
	
	}
	
}