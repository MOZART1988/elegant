<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
global $woo_options, $woocommerce;
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php if ( $woo_options['woo_boxed_layout'] == 'true' ) echo 'boxed'; ?> <?php if (!class_exists('woocommerce')) echo 'woocommerce-deactivated'; ?>">
<head>

<link rel="icon" href="/wp-content/themes/mystile/images/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/wp-content/themes/mystile/images/favicon.ico" type="image/x-icon" />


<title><?php woo_title(''); ?></title>
<?php woo_meta(); ?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'stylesheet_url' ); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/animate.css" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	wp_head();
?>
<!-- WooHead started -->
<?php
	woo_head();
?>
<meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=1">
<style>
	.clbk{
		border-spacing: 0;
	}

	.clbk td{
		padding: 0;
	}

</style>
</head>

<body <?php body_class(); ?>>
<?php woo_top(); ?>
<div class="call-overlay">
	<div class="call-block">
		<div id="callmeform" class="hide-on">
			<div class="call-close"></div>
			<h6>Заказать обратный звонок</h6>
			<table class="clbk">
			<tr><td>Ваше имя:</td></tr>
			<tr><td><input class="text" type="text" maxlength="45" style="width: 240px;" id="cname" /></td></tr>
			<tr><td>Ваш телефон:</td></tr>
			<tr><td><input class="text" type="text" maxlength="35" style="width: 240px;" value="+7" id="cphone" /></td></tr>
			<tr><td>Дополнительная информация:</td></tr>
			<tr><td><textarea class="text" style="width: 240px;" id="csubj"></textarea></td></tr>
			<tr><td><input type="button" value="Перезвоните мне" class="callme_submit callback-submit"></td>
			</tr></table>
			<div id="callme_result"></div>
		</div>
	</div>
</div>
<div id="wrapper">

    <?php woo_header_before(); ?>

	<header id="header" class="col-full">



	    <div id="logo">
		
	    	 <?php
	    	 /*
			    $logo = esc_url( get_template_directory_uri() . '/images/logo.png' );
				if ( isset( $woo_options['woo_logo'] ) && $woo_options['woo_logo'] != '' ) { $logo = $woo_options['woo_logo']; }
				if ( isset( $woo_options['woo_logo'] ) && $woo_options['woo_logo'] != '' && is_ssl() ) { $logo = preg_replace("/^http:/", "https:", $woo_options['woo_logo']); }
			?>
			<?php if ( ! isset( $woo_options['woo_texttitle'] ) || $woo_options['woo_texttitle'] != 'true' ) { ?>
			    <a id="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr( get_bloginfo( 'description' ) ); ?>">
			    	<img src="<?php echo $logo; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
			    </a>
		    <?php } 

		    */?>

			<div class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></div>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			<h3 class="nav-toggle"><a href="#navigation">&#9776; <span><?php _e('Navigation', 'woothemes'); ?></span></a></h3>

		</div>
		<div id="top">
			<nav class="col-full" role="navigation">
				<?php if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'top-menu' ) ) { ?>
				<?php wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'top-nav', 'menu_class' => 'nav fl', 'theme_location' => 'top-menu' ) ); ?>
				<?php } ?>
				<?php
					if ( class_exists( 'woocommerce' ) ) {
						echo '<ul class="nav wc-nav">';
						echo '<li class="info"><p class="p-first">8(495)369-06-40 (10:00-21:00)</p>';
						echo '<li class="info"><p class="p-first">8(495)797-666-0 (10:00-18:00)</p><span class="callme-link">Заказать обратный звонок</span>';
						echo '<li class="info-country"><p class="p-first">Бесплатная доставка от 3000 руб!</p><p class="note-descr">По Москве и России</p><p style="color:#000; text-transform:uppercase; font-size:15px;">Без предоплаты!</p>';
						woocommerce_cart_link();
						//echo '<li class="checkout"><a href="'.esc_url($woocommerce->cart->get_checkout_url()).'">'.__('Checkout','woothemes').'</a></li>';
						echo '</ul>';
					}
				?>
			</nav>
		</div>
        <?php woo_nav_before(); ?>

		<nav id="navigation" class="col-full" role="navigation">

			<?php
			if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary-menu' ) ) {
				wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav fr', 'theme_location' => 'primary-menu' ) );
			} else {
			?>
	        <ul id="main-nav" class="nav fl">
				<?php if ( is_page() ) $highlight = 'page_item'; else $highlight = 'page_item current_page_item'; ?>
				<li class="<?php echo $highlight; ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'woothemes' ); ?></a></li>
				<?php wp_list_pages( 'sort_column=menu_order&depth=6&title_li=&exclude=' ); ?>

			</ul><!-- /#nav -->
	        <?php } ?>
	        <?php echo get_search_form(); ?>

		</nav><!-- /#navigation -->

		<?php woo_nav_after(); ?>

	

	</header><!-- /#header -->

	<?php woo_content_before(); ?>
