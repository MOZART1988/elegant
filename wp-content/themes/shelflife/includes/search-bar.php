<?php
/**
 * Search Bar Component
 *
 * Display a search bar with contact information.
 *
 * @author Matty
 * @since 1.0.0
 * @package WooFramework
 * @subpackage Component
 */
 
$settings = array(
				'telephone' => '', 
				'contact_email' => ''
				);
					
$settings = woo_get_dynamic_values( $settings );
?>
<section id="searchbar" class="fix">
<?php
	if ( $settings['telephone'] != '' || $settings['contact_email'] != '' ) {
?>
	<ul>
		<?php
			if ( $settings['telephone'] != '' ) {
		?>
		<li class="tel"><?php echo $settings['telephone']; ?></li>
		<?php
			}
			
			if ( $settings['contact_email'] != '' ) {	
		?>
		<li class="email"><a href="mailto:<?php echo esc_attr( $settings['contact_email'] ); ?>" title="<?php echo esc_attr( sprintf( __( 'E-mail %s', 'woothemes' ), get_bloginfo( 'name' ) ) ); ?>"><?php echo $settings['contact_email']; ?></a></li>
		<?php
			}
		?>
	</ul>
<?php
	}
?>	
	<div class="search_main fix">
		<span><?php _e( 'Search', 'woothemes' ); ?></span>
	    <form method="get" class="searchform" action="<?php echo home_url( '/' ); ?>" >
	        <input type="text" class="field s" name="s" value="<?php esc_attr_e( 'Search…', 'woothemes' ); ?>" onfocus="if ( this.value == '<?php esc_attr_e( 'Search…', 'woothemes' ); ?>' ) { this.value = ''; }" onblur="if ( this.value == '' ) { this.value = '<?php esc_attr_e( 'Search…', 'woothemes' ); ?>'; }" />
	        <input type="image" src="<?php echo get_template_directory_uri(); ?>/images/ico-search.png" class="search-submit" name="submit" alt="Submit" />
	    </form>    
	</div><!--/.search_main-->

</section>