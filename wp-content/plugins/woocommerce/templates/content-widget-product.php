<?php global $product; ?>
<li>
	<a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
		<div class="img-holder">
			<div class="img-wrapper">
				<?php echo $product->get_image(); ?>
			</div>
		</div>
		<div class="name-wrapper">
			<span class="product-title"><?php echo $product->get_title(); ?></span>
		</div>
	</a>
    <div class="lightb"></div>
    
	<?php if ( ! empty( $show_rating ) ) echo $product->get_rating_html(); ?>
	<?php echo $product->get_price_html(); ?>
    <form class="cart" method="post" enctype='multipart/form-data'>
        <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

        <button type="submit" class="single_add_to_cart_button button alt">Купить</button>

        <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
    </form>
</li>

