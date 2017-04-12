<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<center>
	<img src="<?php echo get_template_directory_uri() ?>/images/banner_1.png" style="width:133px" alt="Бесплатная доставка от 3000 руб">
	<img src="<?php echo get_template_directory_uri() ?>/images/banner_2.png" style="width:133px" alt="Бесплатная доставка от 3000 руб">
	<img src="<?php echo get_template_directory_uri() ?>/images/banner_3.png" style="width:133px" alt="Бесплатная доставка от 3000 руб">
</center>
<h1 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h1>
