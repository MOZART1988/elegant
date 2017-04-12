<?php
if ( ! is_admin() ) {
	add_action( 'wp_enqueue_scripts', 'woothemes_add_javascript' );
	add_action( 'wp_print_styles', 'woothemes_add_css' );
}

if ( ! function_exists( 'woothemes_add_javascript' ) ) {
	function woothemes_add_javascript() {
/*
		// Use Google jQuery minified script instead
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery',  'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
*/		
		
		wp_register_script( 'flexslider', get_template_directory_uri() . '/includes/js/jquery.flexslider.min.js', array( 'jquery' ) );
		wp_register_script( 'prettyPhoto', get_template_directory_uri() . '/includes/js/jquery.prettyPhoto.js', array( 'jquery' ) );

		wp_enqueue_script( 'third party', get_template_directory_uri() . '/includes/js/third-party.js', array( 'jquery' ) );
		wp_enqueue_script( 'general', get_template_directory_uri() . '/includes/js/general.js', array( 'jquery', 'flexslider', 'prettyPhoto' ), time() );
		
		/* Setup strings to be sent through to the general.js file. */
		$settings = array(
						'slider_hover' => 'true', 
						'slider_speed' => '7', 
						'slider_animation_speed' => '0.6'
						);
							
		$settings = woo_get_dynamic_values( $settings );
		
		if ( strtolower( $settings['slider_speed'] ) == 'off' ) {
			$settings['slider_autoplay'] = 'false';
			$settings['slider_speed'] = '0';
		} else {
			$settings['slider_autoplay'] = 'true';
		}
		
		$settings['slider_animation_speed'] = $settings['slider_animation_speed'] * 1000;
		$settings['slider_speed'] = $settings['slider_speed'] * 1000;
		
		$settings['slider_effect'] = 'fade';
		$settings['slider_sliding_direction'] = 'horizontal'; 
		
		/* Specify variables to be made available to the general.js file. */
		wp_localize_script( 'general', 'woo_localized_data', $settings );
		
		do_action( 'woothemes_add_javascript' );
	} // End woothemes_add_javascript()
}

if ( ! function_exists( 'woothemes_add_css' ) ) {
	function woothemes_add_css () {
		wp_register_style( 'prettyPhoto', get_template_directory_uri() . '/includes/css/prettyPhoto.css' );
	
		wp_enqueue_style( 'prettyPhoto' );
	
		do_action( 'woothemes_add_css' );
	} // End woothemes_add_css()
}
?>