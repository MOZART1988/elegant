<?php
/**
 * Loop Price
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<span class="amount"><?php echo $price_html; ?></span>

	<div class="buttons">
		<a href="<?php the_permalink(); ?>" style="width:50%; height: 34px; float:left; padding: 8px 0" class="button wc-forward">Подробнее</a>
	    <form class="cart" method="post" enctype='multipart/form-data' style="width: 50%; float:right">
	        <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

			
	        <button type="submit" style="width: 100%; padding: 0" class="single_add_to_cart_button button alt">В корзину</button>

	        <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	    </form>
	</div>
<?php endif; ?>
