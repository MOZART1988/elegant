<?php	
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'phoe_admin_order_data_after_shipping_address' ,10,1 );

function phoe_admin_order_data_after_shipping_address( $order )
{
	
	$additional_fields = get_option('_phoeniixx_custom_additional_fields' );
	
	$additional_fields_default_key = array( 'order_comments' );
	
	unset($additional_fields['order_comments']);
	
	if(!empty( $additional_fields ) && count( $additional_fields ) > 0 )
	{
	
		?>
		
		<div class="address">
		
			<p>
			
				<strong>Additional details:</strong>
				
				<?php
				
				foreach ( $additional_fields as $name => $values ) {
					
					if ( !in_array( $name, $additional_fields_default_key ) ) 
					{
						
						$value = get_post_meta( $order->id, $name ,true);
						
						echo $values['label'].' : ';

						echo $value.'</br>';

					}
					
				}
				
				?>
				
			</p>
		
		</div>
		
		<?php
	
	}

}