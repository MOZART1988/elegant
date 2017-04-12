<?php
/**
 * Plugin Name: Checkout Manager For Woocommerce
 * Description: Checkout manager is an awesome plugin used to add, delete and reorder the billing fields, shipping fields and additional fields of your checkout page.
 * Author:      phoeniixx
 * Version:     1.2.1
 * Author URI:  https://www.phoeniixx.com/
 * Plugin URI:  https://www.phoeniixx.com/
 * Text Domain: phoe_checkout_manager
 * Domain Path: /languages
 */
 
if(!defined( 'ABSPATH' )) exit;

	if(in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		
		define('phoe_checkout_manager_path',plugin_dir_path(  __FILE__  ) );

		class Phoeniixx_Checkout_Manager {
			
			public function __construct() {
				
				add_action( 'admin_menu', array( $this, 'phoe_checkout_admin_menu' ) ); //for admin menu

				include_once( 'classes/class-checkout-fields.php' );
				
				include_once( 'classes/class-address-format.php' );
				
				include_once( 'classes/admin/after-order-shipping-address.php' );
				
				include_once( 'classes/class-order-email.php' );
				
				include_once( 'classes/class-update-user-meta.php' );
				
			}
			

			public function phoe_checkout_admin_menu() {

				add_menu_page(__('Checkout Manager','phoe_checkout_manager'), __('Checkout Manager','phoe_checkout_manager'), 'manage_options' , 'phoe_checkout_manager_menu' , '' , plugin_dir_url( __FILE__ )."assets/img/logo-wp.png" );

				add_submenu_page('phoe_checkout_manager_menu', __('Checkout Manager','phoe_checkout_manager'), __('Checkout Manager','phoe_checkout_manager'), 'manage_options', 'phoe_checkout_manager_menu', array( $this, 'phoe_checkout_manager_menu_f' ) );
			
			}
			
			function sort_options( $a, $b ) {
					
				if ( $a['order'] == $b['order'] ) {
					
					return 0;
					
				}

				return ( $a['order'] < $b['order'] ) ? -1 : 1;
					
			}
			
			
		
			public function phoe_checkout_manager_menu_f()
			{
				
				$plugin_dir_url = plugin_dir_url(__FILE__);
				
				if ( ! empty( $_POST ) ) {

					if( $_POST['reset_field_name'] == 'bill' ){
						
						if ( check_admin_referer( 'reset_field_form_action', 'reset_field_form_nonce_field' ) )
						{
							
							delete_option('_phoeniixx_custom_billing_fields');
							
						}

					}
					else if( $_POST['reset_field_name'] == 'shipp' ){
						
						if ( check_admin_referer( 'reset_field_form_action', 'reset_field_form_nonce_field' ) )
						{
						
							delete_option('_phoeniixx_custom_shipping_fields');
						
						}
						
					}
					else if( $_POST['reset_field_name'] == 'add' ){

						if ( check_admin_referer( 'reset_field_form_action', 'reset_field_form_nonce_field' ) )
						{
							
							delete_option('_phoeniixx_custom_additional_fields');
						
						}
						
					}
				
				}

				$custom_billing_fields = array();
				
				$custom_shipping_fields = array();
				
				$billing_fields_default = $this->default_section_fields('billing_');
				
				$billing_fields_default_key = array( 'billing_first_name', 'billing_last_name', 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_country', 'billing_postcode', 'billing_phone', 'billing_email' );
				
				$shipping_fields_default = $this->default_section_fields('shipping_');
				
				$shipping_fields_default_key = array( 'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_country', 'shipping_postcode' );

				$additionals_fields_default = $this->default_section_fields('additional_');
				
				$additional_fields_default_key = array( 'order_comments' );
				
				if ( isset( $_POST[ 'fieldname' ] ) && isset( $_POST[ 'action' ] ) && $_POST['action'] == 'billing' ) 
				{
					
					if ( check_admin_referer( 'billfield_form_action', 'billfield_form_nonce_field' ) )
					{
					
						$fieldsort  = $_POST['fieldsort'];
						
						$fieldname  = $_POST['fieldname'];
						
						$fieldname = preg_replace('/\s+/', '_', $fieldname);
					
						$fieldtype  = $_POST['fieldtype'];
						
						$fieldlabel  = $_POST['fieldlabel'];
						
						$fieldplaceholder  = $_POST['fieldplaceholder'];
						
						$fieldclass  = $_POST['fieldclass'];
						
						$fieldlabelclass  = $_POST['fieldlabelclass'];
						
						$fieldvalidate  = $_POST['fieldvalidate'];
						
						$fieldrequired  = $_POST['fieldrequired'];
						
						$fieldenabled  = $_POST['fieldenabled'];
						
						$fieldclearRow  = $_POST['fieldclearRow'];
						
						
						for ( $i = 0; $i < sizeof( $fieldname ); $i++ ) 
						{

							if ( ! isset( $fieldname[ $i ] ) || ( '' == $fieldname[ $i ] ) || ( $fieldname[ $i ] == 'billing_' ) )  {
								
								continue;
								
							}
							
							
							$add_billing_fields 	= array();
							
							$add_billing_fields['order']  =  sanitize_text_field( stripslashes( $fieldsort[$i] ) );
						
							$add_billing_fields['fieldname']  = sanitize_text_field( stripslashes( $fieldname[$i] ));
							
							$add_billing_fields['type']  = empty( $fieldtype[$i] ) ? 'text' : sanitize_text_field( stripslashes( $fieldtype[$i] ));
							
							$add_billing_fields['label']  = sanitize_text_field( stripslashes( $fieldlabel[$i] ));
							
							$add_billing_fields['placeholder']  = sanitize_text_field( stripslashes( $fieldplaceholder[$i] ));
							
							$add_billing_fields['class']  =  empty( $fieldclass[$i] ) ? array() : array_map( 'wc_clean', explode( ',', $fieldclass[$i] ) );
							
							$add_billing_fields['label_class']  = empty( $fieldlabelclass[$i] ) ? array() : array_map( 'wc_clean', explode( ',', $fieldlabelclass[$i] ) );
		
							$add_billing_fields['validate']  = empty( $fieldvalidate[$i] ) ? array() : explode( ',', $fieldvalidate[$i] );
							
							$add_billing_fields['required']  = sanitize_text_field( stripslashes( $fieldrequired[$i] ));
							
							$add_billing_fields['enabled']  = sanitize_text_field( stripslashes( $fieldenabled[$i] ));
							
							$add_billing_fields['clear']  = sanitize_text_field( stripslashes( $fieldclearRow[$i] ));
							
							$custom_billing_fields[ sanitize_text_field( stripslashes( $fieldname[$i] )) ] = apply_filters( 'custom_options_save_data', $add_billing_fields, $i );
							
							
						}
						
						
						uasort( $custom_billing_fields, array( $this, 'sort_options' ) );
						
						//$custom_billing_fields = array_values($custom_billing_fields);
						
						update_option('_phoeniixx_custom_billing_fields', $custom_billing_fields );
					
					}
					
				}
				else if ( isset( $_POST[ 'fieldname' ] ) && isset( $_POST[ 'action' ] ) && $_POST['action'] == 'shipping' ) 
				{

					if ( check_admin_referer( 'shippfield_form_action', 'shippfield_form_nonce_field' ) )
					{
						
						$fieldsort  = $_POST['fieldsort'];
						
						$fieldname  = $_POST['fieldname'];
						
						$fieldname = preg_replace('/\s+/', '_', $fieldname);
						
						$fieldtype  = $_POST['fieldtype'];
						
						$fieldlabel  = $_POST['fieldlabel'];
						
						$fieldplaceholder  = $_POST['fieldplaceholder'];
						
						$fieldclass  = $_POST['fieldclass'];
						
						$fieldlabelclass  = $_POST['fieldlabelclass'];
						
						$fieldvalidate  = $_POST['fieldvalidate'];
						
						$fieldrequired  = $_POST['fieldrequired'];
						
						$fieldenabled  = $_POST['fieldenabled'];
						
						$fieldclearRow  = $_POST['fieldclearRow'];
						
						
						for ( $i = 0; $i < sizeof( $fieldname ); $i++ ) 
						{

							if ( ! isset( $fieldname[ $i ] ) || ( '' == $fieldname[ $i ] ) || ( $fieldname[ $i ] == 'shipping_' ) )  {
								
								continue;
								
							}
							
							

							$add_shipping_fields 	= array();
							
							$add_shipping_fields['order']  =  sanitize_text_field( stripslashes( $fieldsort[$i] ) );
						
							$add_shipping_fields['fieldname']  = sanitize_text_field( stripslashes( $fieldname[$i] ));
							
							$add_shipping_fields['type']  = empty( $fieldtype[$i] ) ? 'text' : sanitize_text_field( stripslashes( $fieldtype[$i] ));
							
							$add_shipping_fields['label']  = sanitize_text_field( stripslashes( $fieldlabel[$i] ));
							
							$add_shipping_fields['placeholder']  = sanitize_text_field( stripslashes( $fieldplaceholder[$i] ));
							
							$add_shipping_fields['class']  =  empty( $fieldclass[$i] ) ? array() : array_map( 'wc_clean', explode( ',', $fieldclass[$i] ) );
							
							$add_shipping_fields['label_class']  = empty( $fieldlabelclass[$i] ) ? array() : array_map( 'wc_clean', explode( ',', $fieldlabelclass[$i] ) );
		
							$add_shipping_fields['validate']  = empty( $fieldvalidate[$i] ) ? array() : explode( ',', $fieldvalidate[$i] );
							
							$add_shipping_fields['required']  = sanitize_text_field( stripslashes( $fieldrequired[$i] ));
							
							$add_shipping_fields['enabled']  = sanitize_text_field( stripslashes( $fieldenabled[$i] ));
							
							$add_shipping_fields['clear']  = sanitize_text_field( stripslashes( $fieldclearRow[$i] ));
							
							$custom_shipping_fields[ sanitize_text_field( stripslashes( $fieldname[$i] )) ] = apply_filters( 'custom_options_save_data', $add_shipping_fields, $i );
							
							
						}
						
						
						uasort( $custom_shipping_fields, array( $this, 'sort_options' ) );
						
						//$custom_billing_fields = array_values($custom_billing_fields);
						
						update_option('_phoeniixx_custom_shipping_fields', $custom_shipping_fields );
						
				
					}
					
				}
				else if ( isset( $_POST[ 'fieldname' ] ) && isset( $_POST[ 'action' ] ) && $_POST['action'] == 'additional' ) 
				{
					
					if ( check_admin_referer( 'addfield_form_action', 'addfield_form_nonce_field' ) )
					{
					
						$add_additional_field 	= array();
						
						//print_r($_POST['fieldname']);
						$fieldsort  = $_POST['fieldsort'];
						
						$fieldname  = $_POST['fieldname'];
						
						$fieldname = preg_replace('/\s+/', '_', $fieldname);
						
						$fieldtype  = $_POST['fieldtype'];
						
						$fieldlabel  = $_POST['fieldlabel'];
						
						$fieldplaceholder  = $_POST['fieldplaceholder'];
						
						$fieldclass  = $_POST['fieldclass'];
						
						$fieldlabelclass  = $_POST['fieldlabelclass'];
						
						$fieldvalidate  = $_POST['fieldvalidate'];
						
						$fieldrequired  = $_POST['fieldrequired'];
						
						$fieldenabled  = $_POST['fieldenabled'];
						
						$fieldclearRow  = $_POST['fieldclearRow'];
						
						
						for ( $i = 0; $i < sizeof( $fieldname ); $i++ ) 
						{

							if ( ! isset( $fieldname[ $i ] ) || ( '' == $fieldname[ $i ] ) || ( $fieldname[ $i ] == 'additional_' ) )  {
								
								continue;
								
							}
							
							//echo $i;

							$add_additional_fields 	= array();

							$add_additional_fields['order']  =  sanitize_text_field( stripslashes( $fieldsort[$i] ) );
						
							$add_additional_fields['fieldname']  = sanitize_text_field( stripslashes( $fieldname[$i] ));
							
							$add_additional_fields['type']  = empty( $fieldtype[$i] ) ? 'text' : sanitize_text_field( stripslashes( $fieldtype[$i] ));
							
							$add_additional_fields['label']  = sanitize_text_field( stripslashes( $fieldlabel[$i] ));
							
							$add_additional_fields['placeholder']  = sanitize_text_field( stripslashes( $fieldplaceholder[$i] ));
							
							$add_additional_fields['class']  =  empty( $fieldclass[$i] ) ? array() : array_map( 'wc_clean', explode( ',', $fieldclass[$i] ) );
							
							$add_additional_fields['label_class']  = empty( $fieldlabelclass[$i] ) ? array() : array_map( 'wc_clean', explode( ',', $fieldlabelclass[$i] ) );
		
							$add_additional_fields['validate']  = empty( $fieldvalidate[$i] ) ? array() : explode( ',', $fieldvalidate[$i] );
							
							$add_additional_fields['required']  = sanitize_text_field( stripslashes( $fieldrequired[$i] ));
							
							$add_additional_fields['enabled']  = sanitize_text_field( stripslashes( $fieldenabled[$i] ));
							
							$add_additional_fields['clear']  = sanitize_text_field( stripslashes( $fieldclearRow[$i] ));
							
							$add_additional_field[ sanitize_text_field( stripslashes( $fieldname[$i] )) ] = $add_additional_fields;
							
							
							
						}
						
						uasort( $add_additional_field, array( $this, 'sort_options' ) );
						
						update_option('_phoeniixx_custom_additional_fields', $add_additional_field );
						
					}
					
				}
				
				wp_enqueue_style( 'woocommerce_admin_styles');
				
				wp_enqueue_script('jquery');
				
				wp_enqueue_script('jquery-ui-core');
				
				wp_enqueue_script('jquery-ui-tabs');
				
				wp_enqueue_script( 'select2' );
				
				wp_enqueue_script('jquery-ui-sortable');
				
				$is_default = 0;

				$billing_fields = get_option('_phoeniixx_custom_billing_fields' );
				
				//$count_in_db = count( $billing_fields );
				
				if( empty($billing_fields) )
				{

					$billing_fields = $billing_fields_default;
					
					$is_default = 1;
				
				}
				
				$shipping_fields = get_option('_phoeniixx_custom_shipping_fields' );
				
				//$count_in_db_shipping = count( $shipping_fields );
				
				if( empty($shipping_fields) )
				{

					$shipping_fields = $shipping_fields_default;
					
					$is_default_ship = 1;
				
				}

				$additional_fields = get_option('_phoeniixx_custom_additional_fields' );
				
				//$count_in_db_shipping = count( $shipping_fields );
				
				if( empty($additional_fields) )
				{

					$additional_fields = $additionals_fields_default;
					
					$is_default_add = 1;
				
				}
				

				?>
				
					<div class="wrap">
					
					<?php 
					
						if(isset($_GET['tab'])){
			
							$tab = sanitize_text_field( $_GET['tab'] );
						}else{
							
							$tab ='';
						}
					
						?>
					
					<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
						<a class="nav-tab <?php if($tab == 'general' || $tab == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=phoe_checkout_manager_menu&amp;tab=general">General</a>
						
						<a class="nav-tab <?php if($tab == 'premium'){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=phoe_checkout_manager_menu&amp;tab=premium">Premium</a>	
					</h2>
					
					<?php if($tab == 'general' || $tab == ''){?>
					
						<div id="poststuff">
							
							<!--<h2>Phoeniixx WooCommerce Checkout Manager</h2>-->
							
							
							
							<div class="meta-box-sortables" id="normal-sortables">
								<div class="" id="pho_wcpc_box">
									
									<div class="inside">
										<div class="pho_premium_box">

											<div class="column two">
												<!-----<h2>Get access to Pro Features</h2>----->

													<div class="pho-upgrade-btn">
														<a href="https://www.phoeniixx.com/product/checkout-manager-woocommerce/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>assets/img/premium-btn.png" /></a>
													</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							
							
							<div id="post-body" class="metabox-holder columns-2">
							
								<div class="postbox-container-2" id="">
								
								<form name="reset_field_form" id="reset_field_form" method="post">
								
									<?php wp_nonce_field( 'reset_field_form_action', 'reset_field_form_nonce_field' ); ?>
										
									<input type="hidden" id="reset_field_id" name="reset_field_name" />
									
								</form>

								<div class="meta-box-sortables ui-sortable" id="normal-sortables">
										
									<div class="postbox billing_area closed" id="woocommerce-product-data">
												
										<button aria-expanded="false" class="handlediv button-link" type="button">
											
											<span class="screen-reader-text">Toggle panel: Product Data</span><span aria-hidden="true" class="toggle-indicator"></span>
										
										</button>
										
										<h2 class="hndle ui-sortable-handle">
										
											<span>Billing Fields</span>
											
											<a href="javascript:void(0);" id="add_new_billfield" class="page-title-action">Add New</a>
											
											<a href="javascript:void(0);" id="billfield_save" class="page-title-action">Save</a>
											
											<a href="javascript:void(0);" id="billfield_reset" class="page-title-action">Reset</a>
											
										</h2>
										
										<div class="inside">
												
											<form class="billfield_form" method="post" >
											
												<?php wp_nonce_field( 'billfield_form_action', 'billfield_form_nonce_field' ); ?>
											
												<input type="hidden" name="action" value="billing" />
												
												<div class="panel-wrap product_data">
													
													<ul style="" class="product_data_tabs wc-tabs">
													
														<?php
														
															$i = 0;
															
															foreach ( $billing_fields as $key => $billing_field )
															{
																//echo $key;
																
																?>
																
																	<li class="general_options">
																		<input type="hidden" class="fieldsort" name="fieldsort[<?php echo $i; ?>]" value="<?php echo $i; ?>">
																		<a href="#<?php echo $key; ?>"><?php echo $key; ?></a>
																		
																	</li>
																
																<?php
																	
																$i++;
																
															}
														
														?>
					
													</ul>
															
														<?php
														
															$i1 = 0;
															
															foreach ( $billing_fields as $key => $billing_field )
															{
																//echo $key;
																
																//echo $is_default;
																
																if($is_default == 1)
																{
																	$billing_field['enabled'] = 1; 
																	
																	//echo 'in';
																	
																}
																
																?>
																
																	<div class="panel woocommerce_options_panel" id="<?php echo $key; ?>" style="display: block;">
																		
																		<?php
																			
																			//print_r( in_array( $key,$billing_fields_default ) );
																			$class = '';
																			$custom = 0;
																			if( in_array( $key,$billing_fields_default_key ) )
																			{
																				
																				$custom = 1;
																				
																			}
																			
																			if($custom == 1)
																			{
																				
																				$class = 'disabled=""';
																				
																			}
																			
																			
																		?>
																		<div class="options_group hide_if_grouped">

																			<h3><?php echo $key; 
																			
																				if( !in_array( $key,$billing_fields_default_key ) )
																				{
																					?>
																					
																						<a class="button remove_checkout_field" remove-key="<?php echo $key; ?>" href="javascript:void(0);">Remove</a>
																					
																					<?php
																				}	
																				
																				?>
																					
																			</h3>
																				<table>
																					<tbody>
																						<tr>                
																							<td class="err_msgs" colspan="2"></td>
																						</tr>
																						<tr>                
																							<td width="40%">Name</td>
																							<td>
																								<input type="hidden" class="remove_key_field_<?php echo $key; ?>" name="fieldname[<?php echo $i1; ?>]" value="<?php echo $key; ?>">
																								<input placeholder="always use 'billing_' as prefix" type="text" <?php echo $class; ?> style="width:250px;" class="remove_key_field_<?php echo $key; ?>" value="<?php echo $key; ?>" name="fieldname[<?php echo $i1; ?>]" />
																							</td>
																						</tr>
																						<tr>                   
																							<td>Type</td>
																							<td>
																								<input type="hidden" class="phoen_change_type_<?php echo $i1; ?>" name="fieldtype[<?php echo $i1; ?>]" value="<?php if(!isset($billing_field['type']) || $billing_field['type'] == '' ) { echo "text"; }else{ echo $billing_field['type']; } ?>">
																								
																								<select class="phoen_field_type" <?php echo $class; ?> data-id="<?php echo $i1; ?>" style="width:250px;" >
																																																	
																									<option value="">Select type</option>

																									<option value="text" <?php if( $billing_field['type'] == 'text' || $billing_field['type'] == '' ) { ?> selected="" <?php } ?> >text field</option>
																									
																									<option value="textarea" <?php if( $billing_field['type'] == 'textarea' ) { ?> selected="" <?php } ?> >textarea</option>
																									
																								</select>
																								
																							</td>
																						</tr>                
																						<tr>
																							<td>Label</td>
																							<td><input type="text" style="width:250px;" value="<?php echo $billing_field['label']; ?>" name="fieldlabel[<?php echo $i1; ?>]"></td>
																						</tr>
																						<tr>                    
																							<td>Placeholder</td>
																							<td><input type="text" style="width:250px;" value="<?php echo $billing_field['placeholder']; ?>" name="fieldplaceholder[<?php echo $i1; ?>]"></td>
																						</tr>   
																						<tr>
																							<td>Class</td>
																							<td>
																							<input type="text" style="width:250px;" value="<?php if(!empty($billing_field['class']) ){ echo implode(",", $billing_field['class'] ); } ?>" placeholder="Seperate classes with comma" name="fieldclass[<?php echo $i1; ?>]"></td>
																						</tr>
																						<tr>
																							<td>Label Class</td>
																							<td><input type="text" style="width:250px;" value="<?php if(!empty($billing_field['label_class']) ){ echo implode(",", $billing_field['label_class'] ); } ?>" placeholder="Seperate classes with comma" name="fieldlabelclass[<?php echo $i1; ?>]"></td>
																						</tr>                                   
																						<tr>                    
																							<td>Validation</td>
																							<td>
																							<?php
																							//print_r( $billing_field['validate']);
																							?>
																								<input type="hidden" class="phoen_change_validation_<?php echo $i1; ?>" name="fieldvalidate[<?php echo $i1; ?>]" value="<?php echo $billing_field['validate'][0]; ?>">
																								<select style="width:250px;" data-id="<?php echo $i1; ?>" class="billing_field_validation" placeholder="Select validations" >
																									<option value=""></option>
																									<option <?php if( $billing_field['validate'][0] == 'email' ){ ?> selected="" <?php } ?> value="email">Email</option>
																									<option <?php if( $billing_field['validate'][0] == 'phone' ){ ?> selected="" <?php } ?> value="phone">Phone</option>
																								</select>
																							</td>
																						</tr>  
																						<tr>  
																							<td>&nbsp;</td>                     
																							<td>             
																																											
																								<input type="checkbox" <?php if($billing_field['required'] == true ){ ?> checked="" <?php } ?> value="1" name="fieldrequired[<?php echo $i1; ?>]">
																								<label>Required</label><br>
																								
																								<input type="checkbox" <?php if($billing_field['clear'] == true ){ ?> checked="" <?php } ?> value="1" name="fieldclearRow[<?php echo $i1; ?>]">
																								<label>Clear Row</label><br>
																								
																								<input type="checkbox" <?php if($billing_field['enabled'] == true ){ ?> checked="" <?php } ?> value="1" name="fieldenabled[<?php echo $i1; ?>]">
																								<label>Enabled</label>
																							</td>                    
																						</tr>  
																						
																					</tbody>
																				
																				</table>
																				
																		</div>
																			
																	</div>
																
																<?php
																	
																$i1++;
																
															}
														
														?>

													</div>
												
											</form>
										
										</div>
										
									</div>
									
									
									
									<div class="postbox shipping_area closed" id="woocommerce-product-data">
												
											<button aria-expanded="false" class="handlediv button-link" type="button">
												
												<span class="screen-reader-text">Toggle panel: Product Data</span><span aria-hidden="true" class="toggle-indicator"></span>
											
											</button>
											
											<h2 class="hndle ui-sortable-handle">
											
												<span>Shipping Fields</span>
												
												<a href="javascript:void(0);" id="add_new_shippfield" class="page-title-action">Add New</a>
												
												<a href="javascript:void(0);" id="shippfield_save" class="page-title-action">Save</a>
												
												<a href="javascript:void(0);" id="shippfield_reset" class="page-title-action">Reset</a>
												
											</h2>
										
										<div class="inside">
												
											<form class="shippfield_form" method="post" >
											
												<?php wp_nonce_field( 'shippfield_form_action', 'shippfield_form_nonce_field' ); ?>
											
												<input type="hidden" name="action" value="shipping" />
												
												<div class="panel-wrap product_data">
													
													<ul style="" class="product_data_tabs wc-tabs">
													
														<?php
														
															$i = 0;
															
															foreach ( $shipping_fields as $key => $shipping_field )
															{
																//echo $key;
																
																?>
																
																	<li class="general_options">
																		<input type="hidden" class="fieldsort" name="fieldsort[<?php echo $i; ?>]" value="<?php echo $i; ?>">
																		<a href="#<?php echo $key; ?>"><?php echo $key; ?></a>
																		
																	</li>
																
																<?php
																	
																$i++;
																
															}
														
														?>
					
													</ul>
															
														<?php
														
															$i1 = 0;
															
															foreach ( $shipping_fields as $key => $shipping_field )
															{
																//echo $key;
																
																//echo $is_default;
																
																if($is_default_ship == 1)
																{
																	$shipping_field['enabled'] = 1; 
																	
																	//echo 'in';
																	
																}
																
																?>
																
																	<div class="panel woocommerce_options_panel" id="<?php echo $key; ?>" style="display: block;">
																		
																		<?php
																			
																			//print_r( in_array( $key,$billing_fields_default ) );
																			$class_ship = '';
																			$custom_ship = 0;
																			if( in_array( $key,$shipping_fields_default_key ) )
																			{
																				
																				$custom_ship = 1;
																				
																			}
																			
																			if($custom_ship == 1)
																			{
																				
																				$class_ship = 'disabled=""';
																				
																			}

																		?>
																		<div class="options_group hide_if_grouped">
																			
																			<h3><?php echo $key; 
																			
																				if( !in_array( $key,$shipping_fields_default_key ) )
																				{
																					?>
																					
																						<a class="button remove_checkout_field" remove-key="<?php echo $key; ?>" href="javascript:void(0);">Remove</a>
																					
																					<?php
																				}	
																				
																				?>
																					
																			</h3>
																			
																				<table>
																					<tbody>
																						<tr>                
																							<td class="err_msgs" colspan="2"></td>
																						</tr>
																						<tr>                
																							<td width="40%">Name</td>
																							<td>
																								<input type="hidden" class="remove_key_field_<?php echo $key; ?>" name="fieldname[<?php echo $i1; ?>]" value="<?php echo $key; ?>">
																								<input placeholder="always use 'shipping_' as prefix" type="text" <?php echo $class_ship; ?> style="width:250px;" class="remove_key_field_<?php echo $key; ?>" value="<?php echo $key; ?>" name="fieldname[<?php echo $i1; ?>]" />
																							</td>
																						</tr>
																						<tr>                   
																							<td>Type</td>
																							<td>
																								<input type="hidden" class="phoen_change_type_s_<?php echo $i1; ?>" name="fieldtype[<?php echo $i1; ?>]" value="<?php if(!isset($shipping_field['type']) || $shipping_field['type'] == '' )  { echo "text"; }else{ echo $shipping_field['type']; } ?>">
																								
																								<select class="phoen_field_type_s" <?php echo $class_ship; ?> data-id="<?php echo $i1; ?>" style="width:250px;" >
																																																	
																									<option value="">Select type</option>

																									<option value="text" <?php if( $shipping_field['type'] == 'text' || $shipping_field['type'] == '' ) { ?> selected="" <?php } ?> >text field</option>
																									
																									<option value="textarea" <?php if( $shipping_field['type'] == 'textarea' ) { ?> selected="" <?php } ?> >textarea</option>
																									
																								</select>
																								
																							</td>
																						</tr>                
																						<tr>
																							<td>Label</td>
																							<td><input type="text" style="width:250px;" value="<?php echo $shipping_field['label']; ?>" name="fieldlabel[<?php echo $i1; ?>]"></td>
																						</tr>
																						<tr>                    
																							<td>Placeholder</td>
																							<td><input type="text" style="width:250px;" value="<?php echo $shipping_field['placeholder']; ?>" name="fieldplaceholder[<?php echo $i1; ?>]"></td>
																						</tr>   
																						<tr>
																							<td>Class</td>
																							<td>
																							<input type="text" style="width:250px;" value="<?php if(!empty($shipping_field['class']) ){ echo implode(",", $shipping_field['class'] ); } ?>" placeholder="Seperate classes with comma" name="fieldclass[<?php echo $i1; ?>]"></td>
																						</tr>
																						<tr>
																							<td>Label Class</td>
																							<td><input type="text" style="width:250px;" value="<?php if(!empty($shipping_field['label_class']) ){ echo implode(",", $shipping_field['label_class'] ); } ?>" placeholder="Seperate classes with comma" name="fieldlabelclass[<?php echo $i1; ?>]"></td>
																						</tr>                                   
																						<tr>                    
																							<td>Validation</td>
																							<td>
																							<?php
																							//print_r( $shipping_field['validate']);
																							?>
																								<input type="hidden" class="phoen_change_validations_<?php echo $i1; ?>" name="fieldvalidate[<?php echo $i1; ?>]" value="<?php echo $shipping_field['validate'][0]; ?>">
																								<select style="width:250px;" data-id="<?php echo $i1; ?>" class="shipping_field_validation" placeholder="Select validations" >
																									<option value=""></option>
																									<option <?php if( $shipping_field['validate'][0] == 'email' ){ ?> selected="" <?php } ?> value="email">Email</option>
																									<option <?php if( $shipping_field['validate'][0] == 'phone' ){ ?> selected="" <?php } ?> value="phone">Phone</option>
																								</select>
																							</td>
																						</tr>  
																						<tr>  
																							<td>&nbsp;</td>                     
																							<td>             
																							
																								<input type="checkbox" <?php if($shipping_field['required'] == true ){ ?> checked="" <?php } ?> value="1" name="fieldrequired[<?php echo $i1; ?>]">
																								<label>Required</label><br>
																								
																								<input type="checkbox" <?php if($shipping_field['clear'] == true ){ ?> checked="" <?php } ?> value="1" name="fieldclearRow[<?php echo $i1; ?>]">
																								<label>Clear Row</label><br>
																								
																								<input type="checkbox" <?php if($shipping_field['enabled'] == true ){ ?> checked="" <?php } ?> value="1" name="fieldenabled[<?php echo $i1; ?>]">
																								<label>Enabled</label>
																							</td>                    
																						</tr>  
																						
																					</tbody>
																				
																				</table>
																				
																		</div>
																			
																	</div>
																
																<?php
																	
																$i1++;
																
															}
														
														?>

													</div>
												
											</form>
										
										</div>
										
									</div>
									
									<div class="postbox additional_area closed" id="woocommerce-product-data">
	
											<button aria-expanded="false" class="handlediv button-link" type="button">
												
												<span class="screen-reader-text">Toggle panel: Product Data</span><span aria-hidden="true" class="toggle-indicator"></span>
											
											</button>
											
											<h2 class="hndle ui-sortable-handle">
											
												<span>Additional Fields</span>
												
												<a href="javascript:void(0);" id="add_new_addfield" class="page-title-action">Add New</a>
												
												<a href="javascript:void(0);" id="addfield_save" class="page-title-action">Save</a>
												
												<a href="javascript:void(0);" id="addfield_reset" class="page-title-action">Reset</a>
												
											</h2>
										
										<div class="inside">

											<form class="addfield_form" method="post" >
											
												<?php wp_nonce_field( 'addfield_form_action', 'addfield_form_nonce_field' ); ?>
											
												<input type="hidden" name="action" value="additional" />
												
												<div class="panel-wrap product_data">
													
													<ul style="" class="product_data_tabs wc-tabs">
													
														<?php
														
															$i = 0;
															
															foreach ( $additional_fields as $key => $additional_field )
															{
																//echo $key;
																
																?>
																
																	<li class="general_options">
																		<input type="hidden" class="fieldsort" name="fieldsort[<?php echo $i; ?>]" value="<?php echo $i; ?>">
																		<a href="#<?php echo $key; ?>"><?php echo $key; ?></a>
																		
																	</li>
																
																<?php
																	
																$i++;
																
															}
														
														?>
					
													</ul>
															
														<?php
														
															$i1 = 0;
															
															foreach ( $additional_fields as $key => $additional_field )
															{
																//echo $key;
																
																//echo $is_default;
																
																if($is_default_add == 1)
																{
																	$additional_field['enabled'] = 1; 
																	
																	//echo 'in';
																	
																}
																
																?>
																
																	<div class="panel woocommerce_options_panel" id="<?php echo $key; ?>" style="display: block;">
																		
																		<?php
																			
																			//print_r( in_array( $key,$billing_fields_default ) );
																			$class_add = '';
																			$custom_add = 0;
																			if( in_array( $key,$additional_fields_default_key ) )
																			{
																				
																				$custom_add = 1;
																				
																			}
																			
																			if($custom_add == 1)
																			{
																				
																				$class_add = 'disabled=""';
																				
																			}
																			

																		?>
																		<div class="options_group hide_if_grouped">
																		
																			<h3><?php echo $key; 
																			
																				if( !in_array( $key,$additional_fields_default_key ) )
																				{
																					?>
																					
																						<a class="button remove_checkout_field" remove-key="<?php echo $key; ?>" href="javascript:void(0);">Remove</a>
																					
																					<?php
																				}	
																				
																				?>
																					
																			</h3>

																				<table>
																					<tbody>
																						<tr>                
																							<td class="err_msgs" colspan="2"></td>
																						</tr>
																						<tr>                
																							<td width="40%">Name</td>
																							<td>
																								<input type="hidden" class="remove_key_field_<?php echo $key; ?>" name="fieldname[<?php echo $i1; ?>]" value="<?php echo $key; ?>">
																								<input placeholder="always use 'additional_' as prefix" type="text" <?php echo $class_add ?> class="remove_key_field_<?php echo $key; ?>" style="width:250px;" value="<?php echo $key; ?>" name="fieldname[<?php echo $i1; ?>]" />
																							</td>
																						</tr>
																						<tr>                   
																							<td>Type</td>
																							<td>
																								<input type="hidden" class="phoen_change_type_s_<?php echo $i1; ?>" name="fieldtype[<?php echo $i1; ?>]" value="<?php if(!isset($additional_field['type']) || $additional_field['type'] == '' ) { echo "text"; }else{ echo $additional_field['type']; } ?>">
																								
																								<select class="phoen_field_type_s" <?php echo $class_add; ?> data-id="<?php echo $i1; ?>" style="width:250px;" >
																																																	
																									<option value="">Select type</option>

																									<option value="text" <?php if( $additional_field['type'] == 'text' || $additional_field['type'] == '' ) { ?> selected="" <?php } ?> >text field</option>
																									
																									<option value="textarea" <?php if( $additional_field['type'] == 'textarea' ) { ?> selected="" <?php } ?> >textarea</option>
																									
																								</select>
																								
																							</td>
																						</tr>                
																						<tr>
																							<td>Label</td>
																							<td><input type="text" style="width:250px;" value="<?php echo $additional_field['label']; ?>" name="fieldlabel[<?php echo $i1; ?>]"></td>
																						</tr>
																						<tr>                    
																							<td>Placeholder</td>
																							<td><input type="text" style="width:250px;" value="<?php echo $additional_field['placeholder']; ?>" name="fieldplaceholder[<?php echo $i1; ?>]"></td>
																						</tr>   
																						<tr>
																							<td>Class</td>
																							<td>
																							<input type="text" style="width:250px;" value="<?php if(!empty($additional_field['class']) ){ echo implode(",", $additional_field['class'] ); } ?>" placeholder="Seperate classes with comma" name="fieldclass[<?php echo $i1; ?>]"></td>
																						</tr>
																						<tr>
																							<td>Label Class</td>
																							<td><input type="text" style="width:250px;" value="<?php if(!empty($additional_field['label_class']) ){ echo implode(",", $additional_field['label_class'] ); } ?>" placeholder="Seperate classes with comma" name="fieldlabelclass[<?php echo $i1; ?>]"></td>
																						</tr>                                   
																						<tr>                    
																							<td>Validation</td>
																							<td>
																							<?php
																							//print_r( $additional_field['validate']);
																							?>
																								<input type="hidden" class="phoen_change_validationa_<?php echo $i1; ?>" name="fieldvalidate[<?php echo $i1; ?>]" value="<?php echo $additional_field['validate'][0]; ?>">
																								<select style="width:250px;" data-id="<?php echo $i1; ?>" class="additional_field_validation" placeholder="Select validations" >
																									<option value=""></option>
																									<option <?php if( $additional_field['validate'][0] == 'email' ){ ?> selected="" <?php } ?> value="email">Email</option>
																									<option <?php if( $additional_field['validate'][0] == 'phone' ){ ?> selected="" <?php } ?> value="phone">Phone</option>
																								</select>
																							</td>
																						</tr>  
																						<tr>  
																							<td>&nbsp;</td>                     
																							<td>
																							
																								<input type="checkbox" <?php if($additional_field['required'] == true ){ ?> checked="" <?php } ?> value="1" name="fieldrequired[<?php echo $i1; ?>]">
																								<label>Required</label><br>
																								
																								<input type="checkbox" <?php if($additional_field['clear'] == true ){ ?> checked="" <?php } ?> value="1" name="fieldclearRow[<?php echo $i1; ?>]">
																								<label>Clear Row</label><br>
																								
																								<input type="checkbox" <?php if($additional_field['enabled'] == true ){ ?> checked="" <?php } ?> value="1" name="fieldenabled[<?php echo $i1; ?>]">
																								<label>Enabled</label>
																							</td>                    
																						</tr>  
																						
																					</tbody>
																				
																				</table>
																				
																		</div>
																			
																	</div>
																
																<?php
																	
																$i1++;
																
															}
														
														?>

													</div>
												
											</form>
										
										</div>
										
									</div>

								</div>

									<div class="meta-box-sortables ui-sortable" id="advanced-sortables"></div>

								</div>
							</div>
						</div>
					<?php }if($tab == 'premium'){
						require_once('checkout_premium.php');
					}
						?>
					</div>
				  <script>
						
					  jQuery(document).ready(function($) {
						  
						jQuery( ".product_data" ).tabs({
							
							event: "click",
							
							activate: function (event, ui) {
								
								var newTab = ui.newTab;
								
								var oldTab = ui.oldTab;
								
								jQuery(newTab).addClass('active'); 
								
								jQuery(oldTab).removeClass('active'); 

							},
							
						});
						
						jQuery( ".product_data li" ).removeClass( "ui-state-default ui-corner-top" );
						
						jQuery('.handlediv').click( function(){
							
							var aria_expanded = jQuery(this).attr('aria-expanded');
							
							if( aria_expanded == 'true' )
							{
								
								jQuery(this).attr('aria-expanded','false');
								
								jQuery(this).parent().addClass('closed');
								
							}
							else
							{
								
								jQuery(this).attr('aria-expanded','true');
								
								jQuery(this).parent().removeClass('closed');
								
							}
							
							//console.log( );
							
						});
						
						jQuery('#add_new_billfield').click( function(){
							
							var loop = jQuery('.billing_area .woocommerce_options_panel').size();
							
							//loop = loop + 1;
							
							console.log(loop);
						
							var html = '<?php
							
							ob_start();
							
							$loop = "{loop}";
							
							include( 'templates/admin/add_field_form_billing.php' );
							
							$html = ob_get_clean();
										
							echo str_replace( array( "\n", "\r" ), '', str_replace( "'", '"', $html ) );
							
							?>';
							
							html = html.replace( /{loop}/g, loop );
							
							var html_li = '<li class="general_options"><input type="hidden" class="fieldsort" name="fieldsort['+loop+']" value="'+loop+'"><a href="#billing_'+loop+'" > billing_ </a></li>';
							
							//console.log(html);
							
							jQuery('.billing_area .product_data').append( html );
							
							//console.log(html_li);
							
							jQuery('.billing_area .product_data_tabs').append( html_li );
							
							//jQuery('.product_data').tabs();
							
							jQuery(".billing_area .product_data").tabs("refresh");

						});
						
						jQuery('.billing_area .product_data_tabs').sortable({
							
							helper: fixWidthHelper,
							
							scrollSensitivity:50,
							
							start:function(event,ui){
								
								ui.item.css('border-style', 'dashed');
								
							},
							
							stop:function(event,ui){
								
								ui.item.removeAttr('style');
								
								$this =  ui.item;
								
								options_row_indexes();
								
							}

						}).disableSelection();
							
						function fixWidthHelper(e, ui) {
							
							ui.children().each(function() {
								
								$(this).width($(this).width());
								
							});
							
							return ui;
							
						}
						
						function options_row_indexes() {
							
							jQuery('.billing_area .product_data .general_options').each(function(index, sel) {
							
								jQuery('.fieldsort', sel).val( parseInt( index ) ); 
								
							
							});
							
						}
						

						jQuery('#add_new_shippfield').click( function(){
							
							var loop = jQuery('.shipping_area .woocommerce_options_panel').size();
							
							//loop = loop + 1;
							
							console.log(loop);
						
							var html = '<?php
							
							ob_start();
							
							$loop = "{loop}";
							
							include( 'templates/admin/add_field_form_shipping.php' );
							
							$html = ob_get_clean();
										
							echo str_replace( array( "\n", "\r" ), '', str_replace( "'", '"', $html ) );
							
							?>';
							
							html = html.replace( /{loop}/g, loop );
							
							var html_li = '<li class="general_options"><input type="hidden" class="fieldsort" name="fieldsort['+loop+']" value="'+loop+'"><a href="#shipping_'+loop+'" > shipping_ </a></li>';
							
							//console.log(html);
							
							jQuery('.shipping_area .product_data').append( html );
							
							//console.log(html_li);
							
							jQuery('.shipping_area .product_data_tabs').append( html_li );
							
							//jQuery('.product_data').tabs();
							
							jQuery(".shipping_area .product_data").tabs("refresh");

						});
						
						jQuery('.shipping_area .product_data_tabs').sortable({
							
							helper: fixWidthHelper,
							
							scrollSensitivity:50,
							
							start:function(event,ui){
								
								ui.item.css('border-style', 'dashed');
								
							},
							
							stop:function(event,ui){
								
								ui.item.removeAttr('style');
								
								$this =  ui.item;
								
								options_row_indexes_s();
								
							}

						}).disableSelection();

						function options_row_indexes_s() {

							jQuery('.shipping_area .product_data .general_options').each(function(index, sel) {
								
							
								jQuery('.fieldsort', sel).val( parseInt( index ) ); 
								
							
							});
							
						}
						
						
						jQuery('#billfield_save').click( function() {
							
							jQuery('.billfield_form').submit();
							
						});
						
						jQuery('#shippfield_save').click( function() {
							
							jQuery('.shippfield_form').submit();
							
						});
						
						jQuery('#addfield_save').click( function() {
							
							jQuery('.addfield_form').submit();
							
						});
						

						jQuery('.billing_field_validation').change(function(){
							
							var id = jQuery(this).attr('data-id');
							
							var this_val = jQuery(this).val();
							
							jQuery('.phoen_change_validation_'+id).val(this_val);
							
						});
						
						
						jQuery('.shipping_field_validation').change(function(){
							
							var id = jQuery(this).attr('data-id');
							
							var this_val = jQuery(this).val();
							
							jQuery('.phoen_change_validations_'+id).val(this_val);
							
						});
						
						jQuery('.additional_field_validation').change(function(){
							
							var id = jQuery(this).attr('data-id');
							
							var this_val = jQuery(this).val();
							
							jQuery('.phoen_change_validationa_'+id).val(this_val);
							
						});
						
						jQuery('.phoen_field_type').change(function(){
							
							var id = jQuery(this).attr('data-id');
							
							var this_val = jQuery(this).val();
							
							jQuery('.phoen_change_type_'+id).val(this_val);
							
						});
						
						jQuery('.phoen_field_type_s').change(function(){
							
							var id = jQuery(this).attr('data-id');
							
							var this_val = jQuery(this).val();
							
							jQuery('.phoen_change_type_s_'+id).val(this_val);
							
						});
						
						
						jQuery('#add_new_addfield').click( function(){
							
							var loop = jQuery('.additional_area .woocommerce_options_panel').size();
							
							//loop = loop + 1;
							
							console.log(loop);
						
							var html = '<?php
							
							ob_start();
							
							$loop = "{loop}";
							
							include( 'templates/admin/add_field_form_additional.php' );
							
							$html = ob_get_clean();
										
							echo str_replace( array( "\n", "\r" ), '', str_replace( "'", '"', $html ) );
							
							?>';
							
							html = html.replace( /{loop}/g, loop );
							
							var html_li = '<li class="general_options"><input type="hidden" class="fieldsort" name="fieldsort['+loop+']" value="'+loop+'"><a href="#additional_'+loop+'" > additional_ </a></li>';
							
							//console.log(html);
							
							jQuery('.additional_area .product_data').append( html );
							
							//console.log(html_li);
							
							jQuery('.additional_area .product_data_tabs').append( html_li );
							
							//jQuery('.product_data').tabs();
							
							jQuery(".additional_area .product_data").tabs("refresh");

						});
						
						
						
						jQuery('.additional_area .product_data_tabs').sortable({
							
							helper: fixWidthHelper,
							
							scrollSensitivity:50,
							
							start:function(event,ui){
								
								ui.item.css('border-style', 'dashed');
								
							},
							
							stop:function(event,ui){
								
								ui.item.removeAttr('style');
								
								$this =  ui.item;
								
								options_row_indexes_a();
								
							}

						}).disableSelection();
						
						
						function options_row_indexes_a() {

							jQuery('.additional_area .product_data .general_options').each(function(index, sel) {
								
							
								jQuery('.fieldsort', sel).val( parseInt( index ) ); 
								
							
							});
							
						}
						
						jQuery('.remove_checkout_field').live('click',function(){
							
							console.log(jQuery(this).attr('remove-key'));
							
							jQuery("li[aria-controls='"+jQuery(this).attr('remove-key')+"']").hide();
							
							jQuery("#"+ jQuery(this).attr('remove-key') ).hide();
							
							jQuery(".remove_key_field_"+ jQuery(this).attr('remove-key') ).val('');

							//console.log($links);
							
						});
						
						
						jQuery('#billfield_reset').click(function(){
							
							jQuery('#reset_field_id').val('bill');
							
							jQuery('#reset_field_form').submit();
							
						});
						
						jQuery('#shippfield_reset').click(function(){
							
							jQuery('#reset_field_id').val('shipp');
							
							jQuery('#reset_field_form').submit();
							
						});
						
						jQuery('#addfield_reset').click(function(){
							
							jQuery('#reset_field_id').val('add');
							
							jQuery('#reset_field_form').submit();
							
						});
						

					  });
					  
				  </script>
				  <style>
				  .postbox-container-2 #woocommerce-product-data.postbox .ui-sortable-handle span {display: inline-block; width: 70%; vertical-align: bottom;}
				  #woocommerce-coupon-data ul.wc-tabs li a, #woocommerce-product-data ul.wc-tabs li a, .woocommerce ul.wc-tabs li a {word-break: break-all;}
				  .woocommerce_options_panel {width: 75% !important; margin-left: 20px; }
				  
				  .pho_premium_box .pho-upgrade-btn > a:focus{ box-shadow:none; outline:none;}
				    
				  </style>
				<?php

			}
			
			function default_section_fields( $section ) {
				
				if($section === 'billing_' || $section === 'shipping_'){
					
					$fields_default = WC()->countries->get_address_fields( WC()->countries->get_base_country(),$section);
				
				}
				elseif($section === 'additional_' )
				{
					
					$fields_default = array(
						'order_comments' => array(
							'type'        => 'textarea',
							'class'       => array('notes'),
							'label'       => __('Order Notes', 'woocommerce'),
							'placeholder' => _x('Notes about your order, e.g. special notes for delivery.', 'placeholder', 'woocommerce')
						)
					);
					
				}
				
				return $fields_default;
					
			}
			
		}

		$Phoeniixx_Checkout_Manager_obj = new Phoeniixx_Checkout_Manager();
	
	}
	else 
	{
		
		add_action('admin_notices', 'phoeniixx_checkout_manager_admin_notice');

		function phoeniixx_checkout_manager_admin_notice() {
			
			global $current_user ;
			
				$user_id = $current_user->ID;
				
			/* Check that the user hasn't already clicked to ignore the message */
				
			if ( ! get_user_meta($user_id, 'phoeniixx_checkout_manager_ignore_notice') ) {
				
				echo '<div class="error"><p>'; 
				
				printf(__('Phoeniixx Checkout Manager could not detect an active Woocommerce plugin. Make sure you have activated it. | <a href="%1$s">Hide Notice</a>'), '?phoeniixx_checkout_manager_nag_ignore=0');
				
				echo "</p></div>";
			}
		}

		add_action('admin_init', 'phoeniixx_checkout_manager_nag_ignore');

		function phoeniixx_checkout_manager_nag_ignore() {
			
			global $current_user;
			
				$user_id = $current_user->ID;
				
				/* If user clicks to ignore the notice, add that to their user meta */
				
				if ( isset($_GET['phoeniixx_checkout_manager_nag_ignore']) && '0' == $_GET['phoeniixx_checkout_manager_nag_ignore'] ) {
					
						 add_user_meta($user_id, 'phoeniixx_checkout_manager_ignore_notice', 'true', true);
						 
				}
		}
		
	}
