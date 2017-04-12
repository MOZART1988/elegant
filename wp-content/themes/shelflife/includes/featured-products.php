<?php
/**
 * Popular Products Component
 *
 * Display X recent popular products.
 *
 * @author Matty
 * @since 1.0.0
 * @package WooFramework
 * @subpackage Component
 */
 
$settings = array(
				'featured_enable' => 'true', 
				'featured_limit' => 12
				);
					
$settings = woo_get_dynamic_values( $settings );

if ( $settings['featured_enable'] == 'true' ) {
?>

<section id="featured">
	
	<h1 class="section-heading"><?php _e( 'Featured Products', 'woothemes' ); ?></h1>
		
	<div class="flexslider">
	
	<ul class="products slides">
<?php
$i = 0;

$args = array( 'post_type' => 'product', 'posts_per_page' => $settings['featured_limit'], 'meta_key' => 'featured', 'meta_value' => 'yes' );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post(); $_product = &new woocommerce_product( $loop->post->ID );
?>

			<li>
				<?php woocommerce_show_product_sale_flash( $post, $_product ); ?>
				<a href="<?php echo get_permalink( $loop->post->ID ); ?>" title="<?php // echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
<?php
if ( has_post_thumbnail( $loop->post->ID ) ) {
	echo get_the_post_thumbnail( $loop->post->ID, 'shop_single' );
} else {
	echo '<img src="' . $woocommerce->plugin_url() . '/assets/images/placeholder.png" alt="Placeholder" width="' . $woocommerce->get_image_size( 'shop_single_image_width' ) . 'px" height="' . $woocommerce->get_image_size( 'shop_single_image_height' ) . 'px" />';
}
?>
				</a>

				<h2><a href="<?php echo get_permalink( $loop->post->ID ); ?>" title="<?php // echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>"><?php the_title(); ?></a></h2>
				
				<a class="featured-price" href="<?php echo get_permalink( $loop->post->ID ); ?>" title="<?php the_title_attribute(); ?>"><?php echo $_product->get_price_html(); ?></a>
				
				<section class="entry">
					<?php the_excerpt(); ?>
				</section>

				<?php woocommerce_template_loop_add_to_cart( $loop->post, $_product ); ?>

			</li>
	<?php endwhile; ?>

	</ul><!--/.featured-1-->
	
	</div>
	
</section>
<?php
}
?>