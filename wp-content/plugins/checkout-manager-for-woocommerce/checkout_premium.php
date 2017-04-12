<?php



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



$plugin_dir_url =  plugin_dir_url( __FILE__ );

?>

<div class="premium-box">

	<div class="premium-box-head">
           <div class="pho-upgrade-btn">
				<a href="https://www.phoeniixx.com/product/checkout-manager-woocommerce/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>assets/img/premium-btn.png"></a>
           </div>
       </div>
           <div class="main-heading">
			   <h1> <img src="<?php echo $plugin_dir_url; ?>assets/img/premium-head.png" />
			   
			  </h1>
           </div>
           <div class="premium-box-container">
			   <div class="description">
					<div class="pho-desc-head">
					<h2>Creat Different Types Of Fields</h2></div>
					
						<div class="pho-plugin-content">
							<p>Option to create Checkbox field,Radio button,Date Time Picker,File-upload field,Text field,Textarea,Select options,Multi Select options,Heading option.</p>
							<div class="pho-img-bg">
							<img src="<?php echo $plugin_dir_url; ?>assets/img/checkout-mamnger-first-img.png" />
							</div>
						</div>
				</div> <!-- description end -->
				 <div class="description">
					<div class="pho-desc-head">
					<h2>Creat User Roles Based Fields</h2></div>
					
						<div class="pho-plugin-content">
							 <p>
							 Option to create user role based fields on the created fields.   
							</p>
							<div class="pho-img-bg">
							<img src="<?php echo $plugin_dir_url; ?>assets/img/checkout-mamnger-scnd-img.png" />
							</div>
						</div>
				</div> <!-- description end -->
				<div class="description">
					<div class="pho-desc-head">
					<h2>Creat Conditional Logic</h2></div>
					
						<div class="pho-plugin-content">
							  <p>
							   Option to create Conditional logic for created fields.
							</p>
							<div class="pho-img-bg">
								<img src="<?php echo $plugin_dir_url; ?>assets/img/checkout-mamnger-thrd-img.png" />
							</div>
						</div>
				</div> <!-- description end -->
             
           </div>

	<div class="pho-upgrade-btn">
        <a target="_blank" href="https://www.phoeniixx.com/product/checkout-manager-woocommerce/"><img src="<?php echo $plugin_dir_url; ?>assets/img/premium-btn.png" /></a>
        </div>
	</div>
	
	<style>
	.premium-box {
		background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
		height: auto;
		width: 100%;
	}
	
	.premium-box-head {
		background: #eae8e7 none repeat scroll 0 0;
		height: 500px;
		text-align: center;
		width: 100%;
	}
	
	.pho-upgrade-btn {
		text-align: center;
	}	
		
	.pho-upgrade-btn a {
		display: inline-block;
		margin-top: 75px;
	}
	
	.main-heading {
		background: #fff none repeat scroll 0 0;
		margin-bottom: -70px;
		text-align: center;
	}
	
	.main-heading h1 {
		margin: 0;
	}
	
	.main-heading img {
		margin-top: -200px;
	}
	
	.premium-box-container {
		margin: 0 auto;
	}
	
	.premium-box-container .description {
		display: block;
		padding: 35px 0;
		text-align: center;
	}
	
	.premium-box-container .description:nth-child(odd) {
		background: #fff none repeat scroll 0 0;
	}
	
	
	.premium-box-container .description:nth-child(even) {
		background: #eae8e7 none repeat scroll 0 0;
	}
	
	.premium-box-container .pho-desc-head::after {
		background: rgba(0, 0, 0, 0) url("<?php echo $plugin_dir_url; ?>assets/img/head-arrow.png") no-repeat scroll 0 0;
		content: "";
		height: 98px;
		position: absolute;
		right: -40px;
		top: -6px;
		width: 69px;
	}
	
	.premium-box-container .pho-desc-head {
		margin: 0 auto;
		position: relative;
		width: 768px;
	}
	
	.premium-box-container .pho-desc-head h2 {
		color: #02c277;
		font-size: 28px;
		font-weight: bolder;
		margin: 0;
		text-transform: capitalize;
	}
	
	.premium-box-container .pho-desc-head h2 {
		line-height: 38px;
	}
	
	.pho-plugin-content {
		margin: 0 auto;
		overflow: hidden;
		width: 768px;
	}
	
	
	.pho-plugin-content p {
		color: #212121;
		font-size: 18px;
		line-height: 32px;
	}
	
	
	.pho-upgrade-btn {
		text-align: center;
	}
	
	.pho-upgrade-btn a {
		display: inline-block;
		margin-top: 75px;
	}
	
	.premium-box-container .description:nth-child(odd) .pho-img-bg {
		background: #f1f1f1 url("<?php echo $plugin_dir_url; ?>assets/img/image-frame-odd.png") no-repeat scroll 100% top;
	}
	
	
	.premium-box-container .description:nth-child(even) .pho-img-bg {
		background: #f1f1f1 url("<?php echo $plugin_dir_url; ?>assets/img/image-frame-even.png") no-repeat scroll 100% top;
	}
	
	.description .pho-plugin-content .pho-img-bg {
		border-radius: 5px 5px 0 0;
		height: auto;
		margin: 0 auto;
		padding: 70px 0 40px;
		width: 750px;
	}
	
	</style>