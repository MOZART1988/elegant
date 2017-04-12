<?php global $wpo_wcpdf; ?>
<table class="head container">
	<tr>
		<td class="invoice-info">
			<?php if ( isset($wpo_wcpdf->settings->template_settings['display_number']) && $wpo_wcpdf->settings->template_settings['display_number'] == 'invoice_number') { ?>
				<h2><?php if( $wpo_wcpdf->get_header_logo_id() ) echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'wpo_wcpdf' ) ); ?> №<?php $wpo_wcpdf->invoice_number(); ?> от <?php $wpo_wcpdf->invoice_date(); ?></h2>
			<?php } else { ?>
				<h2><?php echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'wpo_wcpdf' )) ?></h2>
			<?php } ?>
		</td>
		<td class="header">
		<?php
		if( $wpo_wcpdf->get_header_logo_id() ) {
			$wpo_wcpdf->header_logo();
		} else {
			echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'wpo_wcpdf' ) );
		}
		?>
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_after_document_label', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

<div class="order-data">
	<table>
		<!--
		<tr class="order-number">
			<th><?php _e( 'Order Number:', 'wpo_wcpdf' ); ?></th>
			<td><?php $wpo_wcpdf->order_number(); ?></td>
		</tr>
		<tr class="order-date">
			<th><?php _e( 'Order Date:', 'wpo_wcpdf' ); ?></th>
			<td><?php $wpo_wcpdf->order_date(); ?></td>
		</tr> -->
		<?php do_action( 'wpo_wcpdf_after_order_data', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>
	</table>
</div>

<?php do_action( 'wpo_wcpdf_before_order_details', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

<table class="order-details">
	<thead>
		<tr>
			<th class="product"><?php _e('Product', 'wpo_wcpdf'); ?></th>
			<th class="quantity"><?php _e('Quantity', 'wpo_wcpdf'); ?></th>
			<th class="price"><?php _e('Price', 'wpo_wcpdf'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php $items = $wpo_wcpdf->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) : ?>
		<tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', $item_id, $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>">
			<td class="product">
				<?php $description_label = __( 'Description', 'wpo_wcpdf' ); // registering alternate label translation ?>
				<span class="item-name"><?php echo $item['name']; ?></span>
				<!--
				<?php do_action( 'wpo_wcpdf_before_item_meta', $wpo_wcpdf->export->template_type, $item, $wpo_wcpdf->export->order  ); ?>
				<span class="item-meta"><?php echo $item['meta']; ?></span>
				<dl class="meta">
					<?php $description_label = __( 'SKU', 'wpo_wcpdf' ); // registering alternate label translation ?>
					<?php if( !empty( $item['sku'] ) ) : ?><dt class="sku"><?php _e( 'SKU:', 'wpo_wcpdf' ); ?></dt><dd class="sku"><?php echo $item['sku']; ?></dd><?php endif; ?>
					<?php if( !empty( $item['weight'] ) ) : ?><dt class="weight"><?php _e( 'Weight:', 'wpo_wcpdf' ); ?></dt><dd class="weight"><?php echo $item['weight']; ?><?php echo get_option('woocommerce_weight_unit'); ?></dd><?php endif; ?>
				</dl>
			-->
				<?php do_action( 'wpo_wcpdf_after_item_meta', $wpo_wcpdf->export->template_type, $item, $wpo_wcpdf->export->order  ); ?>
			</td>
			<td class="quantity"><?php echo $item['quantity']; ?></td>
			<td class="price"><?php echo $item['order_price']; ?></td>
		</tr>
		<?php endforeach; endif; ?>
	</tbody>
	<tfoot>
		<?php $total = $wpo_wcpdf->get_woocommerce_totals()?>

	<?php

	preg_match("/(.*\.).*/", $total['shipping']['value'], $matches);

	?>

		<tr>
			<td class="description">Доставка</td>
			<td></td>
			<td><?php echo $matches[1]?></td>
		</tr>
		<tr class="cart_subtotal">
			<td class="description"><?php echo $total['order_total']['label']; ?></td>
			<td></td>
			<td class="price"><span class="totals-price"><?php echo $total['order_total']['value']; ?></span></td>
		</tr>
	</tfoot>
</table>

<table class="signes">
	<tr>
		<td>Руководитель В.П.Сергеев ___________</td>
		<td>Отпустил__________</td>
		<td>Получил(а)________</td>
	</tr>
</table>

<hr>

<div class="shop">

	Повторным покупателям <b>скидка от 3 до 10%!</b><br />
	Elegant-Life.ru <br />
	8-495-797-666-0<br />

</div>


<div class="address billing-address">
	<!-- <h3><?php _e( 'Billing Address:', 'wpo_wcpdf' ); ?></h3> -->
	
	<?php $address = $wpo_wcpdf->get_billing_address(); ?>
	<?php $pattern = '/^.+<br\/>/U'; 
	#preg_match($pattern, $address, $mathes);
	#echo $mathes[0];

	echo $address;
	?>
	<?php
	 echo '<br/>';
	 $wpo_wcpdf->custom_field('billing_tube', 'Метро:'); 
	?>
	

	<?php if ( isset($wpo_wcpdf->settings->template_settings['invoice_email']) ) { ?>
	<div class="billing-email"><?php $wpo_wcpdf->billing_email(); ?></div>
	<?php } ?>
	<?php if ( isset($wpo_wcpdf->settings->template_settings['invoice_phone']) ) { ?>
	<div class="billing-phone"><?php $wpo_wcpdf->billing_phone(); ?></div>
	<?php } ?>
</div>
<div class="customer-notes">
	<?php if ( $wpo_wcpdf->get_shipping_notes() ) : ?>
		<h3><?php _e( 'Customer Notes', 'wpo_wcpdf' ); ?></h3>
		<?php $wpo_wcpdf->shipping_notes(); ?>
	<?php endif; ?>
</div>

<div class="notes">
	<?php if ( $wpo_wcpdf->get_order_notes() ): ?>
		<h3>Примечания к заказу</h3>
		<?php $wpo_wcpdf->order_notes(); ?>
	<?php endif; ?>
</div>


<?php do_action( 'wpo_wcpdf_after_order_details', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>



<?php if ( $wpo_wcpdf->get_footer() ): ?>
<div id="footer">
	<?php $wpo_wcpdf->footer(); ?>
</div><!-- #letter-footer -->
<?php endif; ?>




<? #print_r($total);