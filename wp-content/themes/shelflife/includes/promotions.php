<?php
/**
 * Promotions Component
 *
 * Display X recent promotions.
 *
 * @author Matty
 * @since 1.0.0
 * @package WooFramework
 * @subpackage Component
 */

$settings = array(
				'promotions_limit' => '10'
				);
					
$settings = woo_get_dynamic_values( $settings );

$args = array( 'post_type' => 'promotion', 'numberposts' => $settings['promotions_limit'] );
$promotions = get_posts( $args );

if ( count( $promotions ) > 0 ) {
?>
<section id="promo">
	<div class="flexslider">
		<ul class="slides">
		<?php
			foreach ( $promotions as $k => $post ) {
				setup_postdata( $post );
				
				$meta = get_post_custom( get_the_ID() );
		?>
		    <li>
		    	<?php woo_image( 'width=150' ); ?>
		    	<article>
					<h1><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
					<div class="excerpt"><?php the_excerpt(); ?></div>
		    		<?php
		    			if ( isset( $meta['_button_text'] ) ) {
		    				$text = esc_attr( $meta['_button_text'][0] );
		    				$url = get_permalink( get_the_ID() );
		    				
		    				if ( isset( $meta['_button_url'] ) ) {
		    					$url = esc_url( $meta['_button_url'][0] );
		    				}
		    				
		    				echo '<a class="button sale" href="' . $url . '" title="' . $text . '">' . $text . '</a>';
		    			}
		    		?>
		    	</article>
		    </li>
		<?php
			}
		?>
		</ul>
	</div>
</section>
<?php
}
?>