<?php
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
 
 global $woo_options;
 global $woocommerce;
 
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />

<title><?php woo_title(); ?></title>
<?php woo_meta(); ?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'stylesheet_url' ); ?>" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	wp_head();
	woo_head();
?>
</head>

<body <?php body_class(); ?>>
<?php woo_top(); ?>

	<?php if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'top-menu' ) ) { ?>

	<div id="top">
		<nav class="col-full" role="navigation">
			<?php wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'top-nav', 'menu_class' => 'nav fl', 'theme_location' => 'top-menu' ) ); ?>
		</nav>
	</div><!-- /#top -->

    <?php } ?>

	<header id="header">
	
		<div class="col-full">
		
			<?php
			    $logo = get_template_directory_uri() . '/images/logo.png';
			    if ( isset( $woo_options['woo_logo'] ) && $woo_options['woo_logo'] != '' ) { $logo = $woo_options['woo_logo']; }
			?>
			<?php if ( ! isset( $woo_options['woo_texttitle'] ) || $woo_options['woo_texttitle'] != 'true' ) { ?>
			    <a id="logo" href="<?php bloginfo( 'url' ); ?>" title="<?php bloginfo( 'description' ); ?>">
			    	<img src="<?php echo $logo; ?>" alt="<?php bloginfo( 'name' ); ?>" />
			    </a>
	    	<?php } ?>
	    	
	    	<hgroup>
	    	    
				<h1 class="site-title"><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			      	
			</hgroup>
			
			<?php if ( isset( $woo_options['woo_ad_top'] ) && $woo_options['woo_ad_top'] == 'true' ) { ?>
        	<div id="topad">
				<?php
					if ( isset( $woo_options['woo_ad_top_adsense'] ) && $woo_options['woo_ad_top_adsense'] != '' ) {
						echo stripslashes( $woo_options['woo_ad_top_adsense'] );
					} else {
						if ( isset( $woo_options['woo_ad_top_url'] ) && isset( $woo_options['woo_ad_top_image'] ) )
				?>
					<a href="<?php echo $woo_options['woo_ad_top_url']; ?>"><img src="<?php echo $woo_options['woo_ad_top_image']; ?>" alt="advert" /></a>
				<?php } ?>
			</div><!-- /#topad -->
        	<?php } ?>
        
        </div><!-- /.col-full -->

	</header><!-- /#header -->
	
<div id="wrapper">

	<nav id="navigation" class="col-full" role="navigation">
	
		<?php
		if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary-menu' ) ) {
			wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav fl', 'theme_location' => 'primary-menu' ) );
		} else {
		?>
        <ul id="main-nav" class="nav fl">
			<?php if ( is_page() ) $highlight = 'page_item'; else $highlight = 'page_item current_page_item'; ?>
			<li class="<?php echo $highlight; ?>"><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home', 'woothemes' ); ?></a></li>
			<?php wp_list_pages( 'sort_column=menu_order&depth=6&title_li=&exclude=' ); ?>
		</ul><!-- /#nav -->
        <?php } ?>
        
        <ul class="mini-cart">
		    <li>
		    	<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>" class="cart-parent">
		    		<span> 
		    		<?php 
		    		echo sprintf(_n('<mark>%d item</mark>', '<mark>%d items</mark>', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);
		    		echo $woocommerce->cart->get_cart_total();
		    		?>
		    		</span>
		    	</a>
		    	<?php
 		    		
		            echo '<ul class="cart_list">';
		            echo '<li class="cart-title"><h3>'.__('Your Cart Contents', 'woothemes').'</h3></li>';
		               if (sizeof($woocommerce->cart->cart_contents)>0) : foreach ($woocommerce->cart->cart_contents as $cart_item_key => $cart_item) :
		    	           $_product = $cart_item['data'];
		    	           if ($_product->exists() && $cart_item['quantity']>0) :
		    	               echo '<li class="cart_list_product"><a href="'.get_permalink($cart_item['product_id']).'">';
		    	               
		    	               echo $_product->get_image();
		    	               
		    	               echo apply_filters('woocommerce_cart_widget_product_title', $_product->get_title(), $_product).'</a>';
		    	               
		    	               if($_product instanceof woocommerce_product_variation && is_array($cart_item['variation'])) :
		    	                   echo woocommerce_get_formatted_variation( $cart_item['variation'] );
		    	                 endif;
		    	               
		    	               echo '<span class="quantity">' .$cart_item['quantity'].' &times; '.woocommerce_price($_product->get_price()).'</span></li>';
		    	           endif;
		    	       endforeach;
       
		            	else: echo '<li class="empty">'.__('No products in the cart.','woothemes').'</li>'; endif;
		            	if (sizeof($woocommerce->cart->cart_contents)>0) :
		                echo '<li class="total"><strong>';
		
		                if (get_option('js_prices_include_tax')=='yes') :
		                    _e('Total', 'woothemes');
		                else :
		                    _e('Subtotal', 'woothemes');
		                endif;
		    				
		    			
		    				
		                echo ':</strong>'.$woocommerce->cart->get_cart_total();'</li>';
		
		                echo '<li class="buttons"><a href="'.$woocommerce->cart->get_cart_url().'" class="button">'.__('View Cart &rarr;','woothemes').'</a> <a href="'.$woocommerce->cart->get_checkout_url().'" class="button checkout">'.__('Checkout &rarr;','woothemes').'</a></li>';
		            endif;
		            
		            echo '</ul>';
		
		        ?>
		    </li>
	  	</ul>

	</nav><!-- /#navigation -->