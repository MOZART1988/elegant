<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	if( !function_exists( 'woo_ce_get_export_type_customer_count' ) ) {
		function woo_ce_get_export_type_customer_count() {

			$count = 0;
			// Check if the existing Transient exists
			$cached = get_transient( WOO_CD_PREFIX . '_customer_count' );
			if( $cached == false ) {
				if( $users = woo_ce_get_export_type_count( 'user' ) > 1000 ) {
					$count = sprintf( '~%s+', 1000 );
				} else {
					$post_type = 'shop_order';
					$args = array(
						'post_type' => $post_type,
						'posts_per_page' => -1,
						'fields' => 'ids'
					);
					$woocommerce_version = woo_get_woo_version();
					// Check if this is a WooCommerce 2.2+ instance (new Post Status)
					if( version_compare( $woocommerce_version, '2.2' ) >= 0 ) {
						$args['post_status'] = apply_filters( 'woo_ce_customer_post_status', array( 'wc-pending', 'wc-on-hold', 'wc-processing', 'wc-completed' ) );
					} else {
						$args['post_status'] = apply_filters( 'woo_ce_customer_post_status', woo_ce_post_statuses() );
						$args['tax_query'] = array(
							array(
								'taxonomy' => 'shop_order_status',
								'field' => 'slug',
								'terms' => array( 'pending', 'on-hold', 'processing', 'completed' )
							),
						);
					}
					$order_ids = new WP_Query( $args );
					$count = $order_ids->found_posts;
					if( $count > 100 ) {
						$count = sprintf( '~%s', $count );
					} else {
						$customers = array();
						if( $order_ids->posts ) {
							foreach( $order_ids->posts as $order_id ) {
								$email = get_post_meta( $order_id, '_billing_email', true );
								if( !in_array( $email, $customers ) )
									$customers[$order_id] = $email;
								unset( $email );
							}
							$count = count( $customers );
						}
					}
				}
				set_transient( WOO_CD_PREFIX . '_customer_count', $count, HOUR_IN_SECONDS );
			} else {
				$count = $cached;
			}
			return $count;

		}
	}

	// HTML template for Filter Customers by Order Status widget on Store Exporter screen
	function woo_ce_customers_filter_by_status() {

		$order_statuses = woo_ce_get_order_statuses();

		ob_start(); ?>
<p><label><input type="checkbox" id="customers-filters-status" /> <?php _e( 'Filter Customers by Order Status', 'woocommerce-exporter' ); ?></label></p>
<div id="export-customers-filters-status" class="separator">
	<ul>
		<li>
<?php if( !empty( $order_statuses ) ) { ?>
			<select data-placeholder="<?php _e( 'Choose a Order Status...', 'woocommerce-exporter' ); ?>" name="customer_filter_status[]" multiple class="chzn-select" style="width:95%;">
	<?php foreach( $order_statuses as $order_status ) { ?>
				<option value="<?php echo $order_status->name; ?>"><?php echo ucfirst( $order_status->name ); ?></option>
	<?php } ?>
			</select>
<?php } else { ?>
			<?php _e( 'No Order Status\'s were found.', 'woocommerce-exporter' ); ?>
<?php } ?>
		</li>
	</ul>
	<p class="description"><?php _e( 'Select the Order Status you want to filter exported Customers by. Default is to include all Order Status options.', 'woocommerce-exporter' ); ?></p>
</div>
<!-- #export-customers-filters-status -->
<?php
		ob_end_flush();

	}

	// HTML template for Filter Customers by User Role widget on Store Exporter screen
	function woo_ce_customers_filter_by_user_role() {

		$user_roles = woo_ce_get_user_roles();
		// Add Guest Role to the User Roles list
		if( !empty( $user_roles ) ) {
			$user_roles['guest'] = array(
				'name' => __( 'Guest', 'woocommerce-exporter' ),
				'count' => 1
			);
		}

		ob_start(); ?>
<p><label><input type="checkbox" id="customers-filters-user_role" /> <?php _e( 'Filter Customers by User Role', 'woocommerce-exporter' ); ?></label></p>
<div id="export-customers-filters-user_role" class="separator">
	<ul>
		<li>
<?php if( !empty( $user_roles ) ) { ?>
			<select data-placeholder="<?php _e( 'Choose a User Role...', 'woocommerce-exporter' ); ?>" name="customer_filter_user_role[]" multiple class="chzn-select" style="width:95%;">
	<?php foreach( $user_roles as $key => $user_role ) { ?>
				<option value="<?php echo $key; ?>"><?php echo ucfirst( $user_role['name'] ); ?></option>
	<?php } ?>
			</select>
<?php } else { ?>
			<?php _e( 'No User Roles were found.', 'woocommerce-exporter' ); ?>
<?php } ?>
		</li>
	</ul>
	<p class="description"><?php _e( 'Select the User Roles you want to filter exported Customers by. Default is to include all User Role options.', 'woocommerce-exporter' ); ?></p>
</div>
<!-- #export-customers-filters-user_role -->
<?php
		ob_end_flush();

	}

	// HTML template for jump link to Custom Customer Fields within Order Options on Store Exporter screen
	function woo_ce_customers_custom_fields_link() {

		ob_start(); ?>
<div id="export-customers-custom-fields-link">
	<p><a href="#export-customers-custom-fields"><?php _e( 'Manage Custom Customer Fields', 'woocommerce-exporter' ); ?></a></p>
</div>
<!-- #export-customers-custom-fields-link -->
<?php
		ob_end_flush();

	}


	// HTML template for Custom Customers widget on Store Exporter screen
	function woo_ce_customers_custom_fields() {

		if( $custom_customers = woo_ce_get_option( 'custom_customers', '' ) )
			$custom_customers = implode( "\n", $custom_customers );

		$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/';

		ob_start(); ?>
<form method="post" id="export-customers-custom-fields" class="export-options customer-options">
	<div id="poststuff">

		<div class="postbox" id="export-options customer-options">
			<h3 class="hndle"><?php _e( 'Custom Customer Fields', 'woocommerce-exporter' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'To include additional custom Customer meta in the Export Customers table above fill the Customers text box then click Save Custom Fields. The saved meta will appear as new export fields to be selected from the Customer Fields list.', 'woocommerce-exporter' ); ?></p>
				<p class="description"><?php printf( __( 'For more information on exporting custom Customer meta consult our <a href="%s" target="_blank">online documentation</a>.', 'woocommerce-exporter' ), $troubleshooting_url ); ?></p>
				<table class="form-table">

					<tr>
						<th>
							<label><?php _e( 'Customer meta', 'woocommerce-exporter' ); ?></label>
						</th>
						<td>
							<textarea name="custom_customers" rows="5" cols="70"><?php echo esc_textarea( $custom_customers ); ?></textarea>
							<p class="description"><?php _e( 'Include additional custom Customer meta in your export file by adding each custom Customer meta name to a new line above.<br />For example: <code>Customer UA</code> (new line) <code>Customer IP Address</code>', 'woocommerce-exporter' ); ?></p>
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Save Custom Fields', 'woocommerce-exporter' ); ?>" class="button" />
				</p>
			</div>
			<!-- .inside -->
		</div>
		<!-- .postbox -->

	</div>
	<!-- #poststuff -->
	<input type="hidden" name="action" value="update" />
</form>
<!-- #export-customers-custom-fields -->
<?php
		ob_end_flush();

	}

	function woo_ce_customer_dataset_args( $args, $export_type = '' ) {

		// Check if we're dealing with the Customer Export Type
		if( $export_type <> 'customer' )
			return $args;

		// Merge in the form data for this dataset
		$defaults = array(
			'order_status' => ( isset( $_POST['customer_filter_status'] ) ? woo_ce_format_product_filters( array_map( 'sanitize_text_field', $_POST['customer_filter_status'] ) ) : false ),
			'order_user_roles' => ( isset( $_POST['customer_filter_user_role'] ) ? woo_ce_format_user_role_filters( array_map( 'sanitize_text_field', $_POST['customer_filter_user_role'] ) ) : false )
		);
		$args = wp_parse_args( $args, $defaults );

		// Save dataset export specific options
		// Status
		// User Role

		return $args;

	}
	add_filter( 'woo_ce_extend_dataset_args', 'woo_ce_customer_dataset_args', 10, 2 );


	/* End of: WordPress Administration */

}

// Returns a list of Customer export columns
function woo_ce_get_customer_fields( $format = 'full', $post_ID = 0 ) {

	$export_type = 'customer';

	$fields = array();
	$fields[] = array(
		'name' => 'user_id',
		'label' => __( 'User ID', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'user_name',
		'label' => __( 'Username', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'user_role',
		'label' => __( 'User Role', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'billing_full_name',
		'label' => __( 'Billing: Full Name', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'billing_first_name',
		'label' => __( 'Billing: First Name', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'billing_last_name',
		'label' => __( 'Billing: Last Name', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'billing_company',
		'label' => __( 'Billing: Company', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'billing_address',
		'label' => __( 'Billing: Street Address', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'billing_city',
		'label' => __( 'Billing: City', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'billing_postcode',
		'label' => __( 'Billing: ZIP Code', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'billing_state',
		'label' => __( 'Billing: State (prefix)', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'billing_state_full',
		'label' => __( 'Billing: State', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'billing_country',
		'label' => __( 'Billing: Country', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'billing_phone',
		'label' => __( 'Billing: Phone Number', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'billing_email',
		'label' => __( 'Billing: E-mail Address', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'shipping_full_name',
		'label' => __( 'Shipping: Full Name', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'shipping_first_name',
		'label' => __( 'Shipping: First Name', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'shipping_last_name',
		'label' => __( 'Shipping: Last Name', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'shipping_company',
		'label' => __( 'Shipping: Company', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'shipping_address',
		'label' => __( 'Shipping: Street Address', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'shipping_city',
		'label' => __( 'Shipping: City', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'shipping_postcode',
		'label' => __( 'Shipping: ZIP Code', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'shipping_state',
		'label' => __( 'Shipping: State (prefix)', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'shipping_state_full',
		'label' => __( 'Shipping: State', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'shipping_country',
		'label' => __( 'Shipping: Country (prefix)', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'shipping_country_full',
		'label' => __( 'Shipping: Country', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'total_spent',
		'label' => __( 'Total Spent', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'completed_orders',
		'label' => __( 'Completed Orders', 'woocommerce-exporter' )
	);
	$fields[] = array(
		'name' => 'total_orders',
		'label' => __( 'Total Orders', 'woocommerce-exporter' )
	);

/*
	$fields[] = array(
		'name' => '',
		'label' => __( '', 'woocommerce-exporter' )
	);
*/

	// Drop in our content filters here
	add_filter( 'sanitize_key', 'woo_ce_sanitize_key' );

	// Allow Plugin/Theme authors to add support for additional columns
	$fields = apply_filters( sprintf( WOO_CD_PREFIX . '_%s_fields', $export_type ), $fields, $export_type );

	// Remove our content filters here to play nice with other Plugins
	remove_filter( 'sanitize_key', 'woo_ce_sanitize_key' );

	// Check if we're dealing with an Export Template
	$sorting = false;
	if( !empty( $post_ID ) ) {
		$remember = get_post_meta( $post_ID, sprintf( '_%s_fields', $export_type ), true );
		$hidden = get_post_meta( $post_ID, sprintf( '_%s_hidden', $export_type ), false );
		$sorting = get_post_meta( $post_ID, sprintf( '_%s_sorting', $export_type ), true );
	} else {
		$remember = woo_ce_get_option( $export_type . '_fields', array() );
		$hidden = woo_ce_get_option( $export_type . '_hidden', array() );
	}
	if( !empty( $remember ) ) {
		$remember = maybe_unserialize( $remember );
		$hidden = maybe_unserialize( $hidden );
		$size = count( $fields );
		for( $i = 0; $i < $size; $i++ ) {
			$fields[$i]['disabled'] = ( isset( $fields[$i]['disabled'] ) ? $fields[$i]['disabled'] : 0 );
			$fields[$i]['hidden'] = ( isset( $fields[$i]['hidden'] ) ? $fields[$i]['hidden'] : 0 );
			$fields[$i]['default'] = 1;
			if( isset( $fields[$i]['name'] ) ) {
				// If not found turn off default
				if( !array_key_exists( $fields[$i]['name'], $remember ) )
					$fields[$i]['default'] = 0;
				// Remove the field from exports if found
				if( array_key_exists( $fields[$i]['name'], $hidden ) )
					$fields[$i]['hidden'] = 1;
			}
		}
	}

	switch( $format ) {

		case 'summary':
			$output = array();
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( isset( $fields[$i] ) )
					$output[$fields[$i]['name']] = 'on';
			}
			return $output;
			break;

		case 'full':
		default:
			// Load the default sorting
			if( empty( $sorting ) )
				$sorting = woo_ce_get_option( sprintf( '%s_sorting', $export_type ), array() );
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( !isset( $fields[$i]['name'] ) ) {
					unset( $fields[$i] );
					continue;
				}
				$fields[$i]['reset'] = $i;
				$fields[$i]['order'] = ( isset( $sorting[$fields[$i]['name']] ) ? $sorting[$fields[$i]['name']] : $i );
			}
			// Check if we are using PHP 5.3 and above
			if( version_compare( phpversion(), '5.3' ) >= 0 )
				usort( $fields, woo_ce_sort_fields( 'order' ) );
			return $fields;
			break;

	}

}

// Check if we should override field labels from the Field Editor
function woo_ce_override_customer_field_labels( $fields = array() ) {

	$labels = woo_ce_get_option( 'customer_labels', array() );
	if( !empty( $labels ) ) {
		foreach( $fields as $key => $field ) {
			if( isset( $labels[$field['name']] ) )
				$fields[$key]['label'] = $labels[$field['name']];
		}
	}
	return $fields;

}
add_filter( 'woo_ce_customer_fields', 'woo_ce_override_customer_field_labels', 11 );

// Returns the export column header label based on an export column slug
function woo_ce_get_customer_field( $name = null, $format = 'name' ) {

	$output = '';
	if( $name ) {
		$fields = woo_ce_get_customer_fields();
		$size = count( $fields );
		for( $i = 0; $i < $size; $i++ ) {
			if( $fields[$i]['name'] == $name ) {
				switch( $format ) {

					case 'name':
						$output = $fields[$i]['label'];
						break;

					case 'full':
						$output = $fields[$i];
						break;

				}
				$i = $size;
			}
		}
	}
	return $output;

}

function woo_ce_export_dataset_override_customer( $output = null, $export_type = null ) {

	global $export;

	if( $customers = woo_ce_get_orders( 'customer', $export->args ) ) {
		$export->total_rows = count( $customers );
		// XML, RSS export
		if( in_array( $export->export_format, array( 'xml', 'rss' ) ) ) {
			if( !empty( $export->fields ) ) {
				foreach( $customers as $customer ) {
					if( $export->export_format == 'xml' )
						$child = $output->addChild( apply_filters( 'woo_ce_export_xml_customer_node', sanitize_key( $export_type ) ) );
					else if( $export->export_format == 'rss' )
						$child = $output->addChild( 'item' );
					foreach( array_keys( $export->fields ) as $key => $field ) {
						if( isset( $customer->$field ) ) {
							if( !is_array( $field ) ) {
								if( woo_ce_is_xml_cdata( $customer->$field ) )
									$child->addChild( sanitize_key( $export->columns[$key] ) )->addCData( esc_html( woo_ce_sanitize_xml_string( $customer->$field ) ) );
								else
									$child->addChild( sanitize_key( $export->columns[$key] ), esc_html( woo_ce_sanitize_xml_string( $customer->$field ) ) );
							}
						}
					}
				}
			}
		} else {
			// PHPExcel export
			$output = $customers;
		}
		unset( $customers, $customer );
	}
	return $output;

}

function woo_ce_export_dataset_multisite_override_customer( $output = null, $export_type = null ) {

	global $export;

	$sites = wp_get_sites();
	if( !empty( $sites ) ) {
		foreach( $sites as $site ) {
			switch_to_blog( $site['blog_id'] );
			if( $customers = woo_ce_get_orders( 'customer', $export->args ) ) {
				$export->total_rows = count( $customers );
				// XML, RSS export
				if( in_array( $export->export_format, array( 'xml', 'rss' ) ) ) {
					if( !empty( $export->fields ) ) {
						foreach( $customers as $customer ) {
							if( $export->export_format == 'xml' )
								$child = $output->addChild( apply_filters( 'woo_ce_export_xml_customer_node', sanitize_key( $export_type ) ) );
							else if( $export->export_format == 'rss' )
								$child = $output->addChild( 'item' );
							foreach( array_keys( $export->fields ) as $key => $field ) {
								if( isset( $customer->$field ) ) {
									if( !is_array( $field ) ) {
										if( woo_ce_is_xml_cdata( $customer->$field ) )
											$child->addChild( sanitize_key( $export->columns[$key] ) )->addCData( esc_html( woo_ce_sanitize_xml_string( $customer->$field ) ) );
										else
											$child->addChild( sanitize_key( $export->columns[$key] ), esc_html( woo_ce_sanitize_xml_string( $customer->$field ) ) );
									}
								}
							}
						}
					}
				} else {
					// PHPExcel export
					if( is_null( $output ) )
						$output = $customers;
					else
						$output = array_merge( $output, $customers );
				}
				unset( $customers, $customer );
			}
			restore_current_blog();
		}
	}
	return $output;

}


function woo_ce_get_customers_list() {

	$args = array(
		'fields' => array( 'ID', 'user_email', 'display_name' ),
		// Disabled Customer filters, will re-enable with UI options in future Plugin update
/*
		'orderby' => 'display_name',
		'meta_key' => 'billing_email',
		'meta_value' => null,
		'search_columns'	=> array( 'ID', 'user_login', 'user_email', 'user_nicename' )
*/
	);
	$customers = get_users( $args );
	return $customers;

}

function woo_ce_is_duplicate_customer( $customers = array(), $order = array() ) {

	foreach( $customers as $key => $customer ) {
		if( $customer->billing_email == $order->billing_email ) {
			return $key;
			break;
		}
	}
	return 0;

}
?>