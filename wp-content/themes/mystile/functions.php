<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php



/*-----------------------------------------------------------------------------------*/
/* Start WooThemes Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/

// Define the theme-specific key to be sent to PressTrends.
define( 'WOO_PRESSTRENDS_THEMEKEY', 'zdmv5lp26tfbp7jcwiw51ix9sj389e712' );

// WooFramework init
require_once ( get_template_directory() . '/functions/admin-init.php' );

/*-----------------------------------------------------------------------------------*/
/* Load the theme-specific files, with support for overriding via a child theme.
/*-----------------------------------------------------------------------------------*/

$includes = array(
				'includes/theme-options.php', 			// Options panel settings and custom settings
				'includes/theme-functions.php', 		// Custom theme functions
				'includes/theme-actions.php', 			// Theme actions & user defined hooks
				'includes/theme-comments.php', 			// Custom comments/pingback loop
				'includes/theme-js.php', 				// Load JavaScript via wp_enqueue_script
				'includes/sidebar-init.php', 			// Initialize widgetized areas
				'includes/theme-widgets.php',			// Theme widgets
				'includes/theme-install.php',			// Theme installation
				'includes/theme-woocommerce.php'		// WooCommerce options
				);

// Allow child themes/plugins to add widgets to be loaded.
$includes = apply_filters( 'woo_includes', $includes );

foreach ( $includes as $i ) {
	locate_template( $i, true );
}

/*-----------------------------------------------------------------------------------*/
/* You can add custom functions below */
/*-----------------------------------------------------------------------------------*/

// Add product categories to the "Product" breadcrumb in WooCommerce.
 
// Get breadcrumbs on product pages that read: Home > Shop > Product category > Product Name
add_filter( 'woo_breadcrumbs_trail', 'woo_custom_breadcrumbs_trail_add_product_categories', 20 );
 
function woo_custom_breadcrumbs_trail_add_product_categories ( $trail ) {
  if ( ( get_post_type() == 'product' ) && is_singular() ) {
		global $post;
		
		$taxonomy = 'product_cat';
		
		$terms = get_the_terms( $post->ID, $taxonomy );
		$links = array();
 
		if ( $terms && ! is_wp_error( $terms ) ) {
		$count = 0;
			foreach ( $terms as $c ) {
				$count++;
				if ( $count > 1 ) { continue; }
				$parents = woo_get_term_parents( $c->term_id, $taxonomy, true, ', ', $c->name, array() );
 
				if ( $parents != '' && ! is_wp_error( $parents ) ) {
					$parents_arr = explode( ', ', $parents );
					
					foreach ( $parents_arr as $p ) {
						if ( $p != '' ) { $links[] = $p; }
					}
				}
			}
			
			// Add the trail back on to the end.
			// $links[] = $trail['trail_end'];
			$trail_end = get_the_title($post->ID);
 
			// Add the new links, and the original trail's end, back into the trail.
			array_splice( $trail, 2, count( $trail ) - 1, $links );
			
			$trail['trail_end'] = $trail_end;
		}
	}
 
	return $trail;
} // End woo_custom_breadcrumbs_trail_add_product_categories()
 
/**
 * Retrieve term parents with separator.
 *
 * @param int $id Term ID.
 * @param string $taxonomy.
 * @param bool $link Optional, default is false. Whether to format with link.
 * @param string $separator Optional, default is '/'. How to separate terms.
 * @param bool $nicename Optional, default is false. Whether to use nice name for display.
 * @param array $visited Optional. Already linked to terms to prevent duplicates.
 * @return string
 */
 
if ( ! function_exists( 'woo_get_term_parents' ) ) {
function woo_get_term_parents( $id, $taxonomy, $link = false, $separator = '/', $nicename = false, $visited = array() ) {
	$chain = '';
	$parent = &get_term( $id, $taxonomy );
	if ( is_wp_error( $parent ) )
		return $parent;
 
	if ( $nicename ) {
		$name = $parent->slug;
	} else {
		$name = $parent->name;
	}
 
	if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
		$visited[] = $parent->parent;
		$chain .= woo_get_term_parents( $parent->parent, $taxonomy, $link, $separator, $nicename, $visited );
	}
 
	if ( $link ) {
		$chain .= '<a href="' . get_term_link( $parent, $taxonomy ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $parent->name ) ) . '">'.$parent->name.'</a>' . $separator;
	} else {
		$chain .= $name.$separator;
	}
	return $chain;
} // End woo_get_term_parents()
}






// Redefine woocommerce_output_related_products()
function woocommerce_output_related_products() {
woocommerce_related_products(5,1); // Display 4 products in rows of 2
}





/*-----------------------------------------------------------------------------------*/
/* Don't add any code below here or the sky will fall down */
/*-----------------------------------------------------------------------------------*/






add_action('wp_head', function(){
	wp_enqueue_style( 'bxslider-style', get_template_directory_uri() . '/libs/jquery.bxslider/jquery.bxslider.css');
	wp_enqueue_script('bxslider', get_template_directory_uri() . '/libs/jquery.bxslider/jquery.bxslider.min.js');



	$style_dir = get_template_directory_uri();

	$css = <<<CSS
			

			body .bx-wrapper .bx-viewport{
				border: none !important;
				box-shadow: none;
			}

			body .bx-wrapper .bx-viewport img{
				padding: 0;
			}

			.bx-viewport ul.products li.product:hover{
				transform: none;
			}
	
			.info .tabs{
				margin-bottom: 20px;
				overflow: hidden;
			}

			.info .tabs li{
				float: left;

			}







			.info .tabs .button{
			    display: inline-block;
			    line-height: 15px;
				color: #FFF;
				margin-right: 3px;
				text-transform: none;
				padding: 7px 14px 6px 14px;
				font-size: 13px;
				background: #f46a6a;
				background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(234,234,234,1)), color-stop(100%,rgba(239,1,124,1)));
				background: -webkit-linear-gradient(top, rgba(234,234,234,1) 0%,rgba(239,1,124,1) 100%);
				background: -o-linear-gradient(top, rgba(234,234,234,1) 0%,rgba(239,1,124,1) 100%);
				background: -ms-linear-gradient(top, rgba(234,234,234,1) 0%,rgba(239,1,124,1) 100%);
				background: linear-gradient(to bottom, rgba(234,234,234,1) 0%,rgba(239,1,124,1) 100%);
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eaeaea', endColorstr='#ef017c',GradientType=0 );
				cursor: pointer;
				transition: background 0.5s ease;
				-webkit-transition: background 0.5s ease;
				-moz-transition: background 0.5s ease;
				border-radius: 2px;
			}


			.info .tabs .button.active{
			    color: #000;
			    box-shadow: 0 0 3px #ccc;
			    background: url('$style_dir/images/menu-hover.gif') repeat-x left top;
			}


			.info .tabs .button:hover{
			    box-shadow: 0 0 3px #ccc;
				color: #000;
				background: url('$style_dir/images/menu-hover.gif') repeat-x left top;
			}


			.info .tab-content li{
				display: none;
			}

			.info .tab-content li.active{
				display: block;
			}

			.upsells, .related{
				margin-bottom: 50px;
			}




			.cat-item{
				position: relative;
			}


			#sidebar .widget_product_categories .product-categories .cat-item .subChildren{
				position: absolute;
				overflow: hidden;
				right: -490px;
				padding: 5px;
				width: 490px;
				background: #e0e0e0;
				background: -moz-linear-gradient(top,  #e0e0e0 0%, #f6f6f6 47%, #ededed 100%);
				background: -webkit-linear-gradient(top,  #e0e0e0 0%,#f6f6f6 47%,#ededed 100%);
				background: linear-gradient(to bottom,  #e0e0e0 0%,#f6f6f6 47%,#ededed 100%);
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e0e0e0', endColorstr='#ededed',GradientType=0 );

				display: block;
				box-shadow: 2px 1px 10px gray;
				border-radius: 0 10px 10px 0;
				z-index: 9999;
			}


			#sidebar .widget_product_categories .product-categories .cat-item .subChildren .item{
				display: table;
				float: left;
				width: 120px;
				height: 50px;
				text-align: center;
			}

			.cat-item .subChildren .item span{
				height: 90px;
				padding: 10px;
				width: 120px;
				display: table-cell;
				vertical-align: middle;
				border-radius: 3px;

			}

			.cat-item .subChildren .item a{
				font-size: 12px;
			}

			#sidebar .widget_product_categories .product-categories .cat-item .subChildren .item a:hover{
				color: white;
			}

			#sidebar .widget_product_categories .product-categories .cat-item .subChildren .item a:hover span{
				background: #FF0088;
			}




			.payment_methods.methods{
				display: none !important;
			}


			.loop_add_to_cart_button{
				display: inline-block;
				font-size: 13.5px;
			    height: 35px;
			    background: linear-gradient(to bottom, rgba(234,234,234,1) 0%,rgba(239,1,124,1) 100%);
			    border: none;
			    border-radius: 2px;
			    color: white;
			     line-height: 15px;
			}

			.loop_add_to_cart_button:focus{
			    outline: 0;

			}

			.loop_add_to_cart_button:hover{
				border: 1px solid #ccc;
			    color: #000;
			    background: url('images/menu-hover.gif') repeat-x left top;
			}








			

CSS;
	echo "<style>$css</style>";

});


add_action('wp_footer', function(){

	$js = <<<JAVASCRIPT
			$(function(){
				$('.bx-featured .woocommerce .products').bxSlider({
					moveSlides: 1,
					pager: 0,
					auto: 1,
					minSlides: 5,
					maxSlides: 5,
					slideWidth: 173,
					slideMargin: 10
				});

				$('.bx-related .products').bxSlider({
					moveSlides: 1,
					pager: 0,
					auto: 1,
					minSlides: 5,
					maxSlides: 5,
					slideWidth: 173,
					slideMargin: 10
				});
				

				$('.bx-upsells .products').bxSlider({
					moveSlides: 1,
					pager: 0,
					auto: 1,
					minSlides: 5,
					maxSlides: 5,
					slideWidth: 173,
					slideMargin: 10
				});




				$('.topSliderBx').bxSlider({
					mode: "fade",
					auto: true,
					pager: false
				});



				$('.widget_product_categories.parent > .product-categories > .parent').on('hover', function(){

					var sub = $('<ul/>').addClass('subChildren').appendTo($(this));


					$(this).children(".children").children("li").each(function(){

						var link = $(this).children("div").children("a");

						$('<li/>', {class: 'item'}).appendTo(sub).append($('<a/>', {href: link.attr('href')}).html('<span>'+link.text()+'</span>'));
					});




				});

				$('.product-categories .parent').on('mouseleave', function(){

					$(this).find('.subChildren').remove();

				});


				$(document).mouseup(function (e) {
					var container = $('.product-categories .parent .subChildren');
					if (container.has(e.target).length === 0){
					container.remove();
					}
				});






				
			});


			$('.info .tabs .button').on('click', function(){
				
				var tabName = $(this).attr('id');

				$('.info .tab-content > li').each(function(){
					if($(this).hasClass('active'))
						$(this).removeClass('active');
				});

				$('.info .tabs .button').each(function(){
					if($(this).hasClass('active'))
						$(this).removeClass('active');
				});

				$(this).addClass('active');

				$('.info .tab-content .' + tabName).addClass('active');




			});


JAVASCRIPT;
	echo "<script>$js</script>";

});

/**
 * Добавляем поле на страницу оформления заказа
 */
// add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_field' );

// function my_custom_checkout_field( $checkout ) {


//     woocommerce_form_field( 'my_field_name', array(
//         'type'          => 'text',
//         'class'         => array('my-field-class form-row-wide'),
//         'label'         => __('Метро (заполняется при доставке курьером по Москве и МО)'),
//         'placeholder'   => __('ВМетро (заполняется при доставке курьером по Москве и МО)'),
//         ), $checkout->get_value( 'billing_tube' ));

//     echo '</div>';

// }


add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
     $fields['billing']['billing_tube'] = array(
        'label'     => 'Метро (заполняется при доставке курьером по Москве и МО)',
	    'placeholder'   => '',
	    'required'  => false,
	    'class'     => array('form-row-wide'),
	    'clear'     => true
     );

     return $fields;
}

/**
 * Display field value on the order edit page
 */
 
add_action( 'woocommerce_checkout_update_billing_meta', 'my_custom_checkout_field_billing_meta', 10, 1 );

function my_custom_checkout_field_billing_meta($order){
    echo '<p><strong>'.__('Phone From Checkout Form').':</strong> ' . get_post_meta( $order->id, 'billing_tube', true ) . '</p>';
    if ( ! empty( $_POST['billing_tube'] ) ) {
        update_post_meta( $order_id, 'Метро', sanitize_text_field( $_POST['billing_tube'] ) );
    }
}




add_filter('woocommerce_checkout_fields', function( $fields ) {


	$i = 0;
	$newBiling = array();

	#Впихнем после нулевого ключа массива фамилию
	foreach ($fields['billing'] as $key => $value) {

		if($i == 1)
			$newBiling['billing_last_name'] = [
				'class' => array('form-row-wide'),
				'type' => 'text',
				'label' => 'Фамилия (заполняется для отправки почтой)'
		];
		$newBiling[$key] = $value;
		$i++;

	}
	$fields['billing'] = $newBiling;

	$fields['billing']['billing_postcode']['label'] = 'Почтовый индекс (заполняется для отправки почтой)';
	$fields['billing']['billing_postcode']['type'] = 'text';
	$fields['billing']['billing_postcode']['class'] = array('form-row-wide');
	return $fields;
});





/*
add_action('woocommerce_single_product_summary', function(){

	$tmpl_dir = get_template_directory_uri();

	echo <<<HTML

	<img src="$tmpl_dir/images/banner_1.png" alt="Бесплатная доставка от 3000 руб">

HTML;

});*/



add_action('woocommerce_after_single_product_summary', function(){

	$shipment = get_post(9);
	$sertificats = get_post(33761);

	echo '
		<div class="info">
			<ul class="tabs">
				<li><div id="shipment" class="button">Доставка и оплата</div></li>
				<li><div id="sertificats" class="button">Наши сертификаты</div></li>
				<li><div id="reviews" class="button">Отзывы</div></li>
			</ul>
			<div class="clear"></div>
			<ul class="tab-content">
				<li class="shipment">'.$shipment->post_content.'</li>
				<li class="sertificats">'.$sertificats->post_content.'</li>
                <li class="reviews"></li>
			</ul>
		</div>
		<hr>';

});

add_action( 'product_cat_edit_form_fields', 'wpm_taxonomy_edit_meta_field', 10, 2 );

function wpm_taxonomy_edit_meta_field($term) {
 
 $t_id = $term->term_id;
 $term_meta = get_option( "taxonomy_$t_id" );
  $content = $term_meta['custom_term_meta'] ? wp_kses_post( $term_meta['custom_term_meta'] ) : '';
  $settings = array( 'textarea_name' => 'term_meta[custom_term_meta]' );
  ?>
  <tr class="form-field">
  <th scope="row" valign="top"><label for="term_meta[custom_term_meta]">Любой текст или банеры для каждой категории</label></th>
    <td>
      <?php wp_editor( $content, 'product_cat_details', $settings ); ?>
     
    </td>
  </tr>
<?php
}

add_action( 'edited_product_cat', 'save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_product_cat', 'save_taxonomy_custom_meta', 10, 2 );

function save_taxonomy_custom_meta( $term_id ) {
  if ( isset( $_POST['term_meta'] ) ) {
    $t_id = $term_id;
    $term_meta = get_option( "taxonomy_$t_id" );
    $cat_keys = array_keys( $_POST['term_meta'] );
    foreach ( $cat_keys as $key ) {
      if ( isset ( $_POST['term_meta'][$key] ) ) {
        $term_meta[$key] = wp_kses_post( stripslashes($_POST['term_meta'][$key]) );
      }
    }
    
    update_option( "taxonomy_$t_id", $term_meta );
  }
}


add_action( 'woocommerce_before_main_content', 'wpm_product_cat_archive_add_meta' );

function wpm_product_cat_archive_add_meta() {
  $t_id = get_queried_object()->term_id;
  $term_meta = get_option( "taxonomy_$t_id" );
  $term_meta_content = $term_meta['custom_term_meta'];
  if ( $term_meta_content != '' ) {
    echo '<div class="woo-sc-box normal rounded full">';
      echo apply_filters( 'the_content', $term_meta_content );
    echo '</div>';
  }
}







?>
