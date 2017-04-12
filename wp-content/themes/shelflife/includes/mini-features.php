<?php
/**
 * Mini-Features Component
 *
 * Display X recent mini-features.
 *
 * @author Matty
 * @since 1.0.0
 * @package WooFramework
 * @subpackage Component
 */

$settings = array(
				'features_limit' => '10'
				);
					
$settings = woo_get_dynamic_values( $settings );

$args = array( 'post_type' => 'infobox', 'numberposts' => $settings['features_limit'] );
$infoboxes = get_posts( $args );

if ( count( $infoboxes ) > 0 ) {
?>
<section id="features" class="fix">
<?php
	$count = 0;
	foreach ( $infoboxes as $k => $post ) {
		setup_postdata( $post );
		
		$count++;
		
		$css_class = 'feature-number-' . $count;
		$css_class .= ' feature-id-' . get_the_ID();
		
		if ( $count % 4 == 0 ) {
			$css_class .= ' last';
		}
		
		$meta = get_post_custom( get_the_ID() );
		
		$excerpt = '';
		if ( isset( $meta['mini_excerpt'] ) ) {
			$excerpt = wpautop( strip_tags( $meta['mini_excerpt'][0] ) );
		}
		
		// Determine the type of feature (image or video).
		$feature_type = 'image';
		
		if ( woo_embed( '' ) != '' ) {
			$feature_type = 'video';
		}
		
		$icon_class = array( 'image' => 'img', 'video' => 'vid' );
		
		// Setup the URL for the feature (either the read more link, video URL or the full size image).
		$url = '';
		
		if ( isset( $meta['mini_readmore'] ) ) {
			$url = esc_url( $meta['mini_readmore'][0] );
		}
		
		if ( $url != '' ) {
			$pos = stristr( $url, '?' );
			$delimiter = '?';
			if ( $pos != '' ) {
				$delimiter = '&';
			}
			
			$url .= $delimiter . 'iframe=true&width=95%&height=95%';
		}
		
		if ( isset( $meta['lightbox_url'] ) ) {
			$url = esc_url( $meta['lightbox_url'][0] );	
		} else {
			if ( isset( $meta['mini'] ) && ( $meta['mini'][0] != '' ) ) {
				$url = $meta['mini'][0];
			}
		}
?>
<article class="<?php echo $css_class; ?>">
	<?php if ( $url != '' ) { ?><a rel="lightbox['features']" href="<?php echo $url; ?>" title="<?php echo esc_attr( strip_tags( $excerpt ) ); ?>"><?php } ?>
		<?php woo_image( 'key=mini&link=img&width=220&height=130&class=thumb' ); ?>
		<span class="icon <?php echo $icon_class[$feature_type]; ?>">
			<img src="<?php echo get_template_directory_uri(); ?>/images/ico-features-<?php echo $feature_type; ?>.png" alt="<?php ucfirst( $feature_type ); ?>" />
		</span>
	<?php if ( $url != '' ) { ?></a><?php } ?>
	<h1><?php the_title(); ?></h1>
	<?php echo $excerpt; ?>
</article>
<?php
	}
?>
</section>
<?php
}
?>