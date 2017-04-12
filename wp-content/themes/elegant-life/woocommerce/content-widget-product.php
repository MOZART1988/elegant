<?php global $product; ?>
<li>
	<a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
		<div class="img-holder">
			<div class="img-wrapper">
				<?php echo $product->get_image(); ?>
			</div>
		</div>
		
	</a>
    <div class="lightb hello"></div>
</li>
