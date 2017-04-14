<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php
/**
 * Footer Template
 *
 * Here we setup all logic and XHTML that is required for the footer section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
	global $woo_options;
	
	echo '<div id="footer-home">
  <div class="container">
		<div class="column three">
			<h3>Личный Кабинет</h3>
			<ul>
				<li><a href="/?page_id=7">Личный Кабинет</a></li>
				<li><a href="/?page_id=7">История заказов</a></li>
				<li><a href="/?page_id=87">Отзывы</a></li>
				<li><a href="/?page_id=85">Каталог</a></li>
			</ul> 
		</div>
		<div class="column three">
			<h3>Информация</h3>
			<ul>
				<li><a href="/?page_id=13">Качество и гарантии</a></li>
				<li><a href="/?page_id=15">Контакты</a></li>
				<li><a href="/?page_id=2">О магазине</a></li>
				<li><a href="/?page_id=9">Доставка и оплата</a></li>
			</ul>
		</div>
		<div class="column three">
			<h3>Дополнительно</h3>
			<ul> 
				<li><a href="/">Главная</a></li>
				<li><a href="/?page_id=15">Обратная связь</a></li>
			</ul>
		</div>
		<span class="ctr"></span>

		<!--custom block --> 
		<div class="customblock_footer column "> 
			<div>
			</div>
		<!--Network icons--> 
		<!--end Network icons-->    
		</div> 
		<!--end custom block-->  
		<div class="column contact">
			<ul>
				<li class="phone_f">+7(495)369-06-40</li>
				<li class="email_f"><a href="mailto:Elegant-Life@mail.ru">Elegant-Life@mail.ru</a></li>
				<li class="address_f">г.Москва, торгово-офисный центр «Olympic Plaza» Москва, просп. Мира, 33, корп.1</li>
			</ul>
		</div>
	</div>  
</div></div><div class="footer-wrap">';

	$total = 4;
	if ( isset( $woo_options['woo_footer_sidebars'] ) && ( $woo_options['woo_footer_sidebars'] != '' ) ) {
		$total = $woo_options['woo_footer_sidebars'];
	}

	if ( ( woo_active_sidebar( 'footer-1' ) ||
		   woo_active_sidebar( 'footer-2' ) ||
		   woo_active_sidebar( 'footer-3' ) ||
		   woo_active_sidebar( 'footer-4' ) ) && $total > 0 ) {

?>
	<?php woo_footer_before(); ?>
	
		<section id="footer-widgets" class="col-full col-<?php echo $total; ?> fix">
	
			<?php $i = 0; while ( $i < $total ) { $i++; ?>
				<?php if ( woo_active_sidebar( 'footer-' . $i ) ) { ?>
	
			<div class="block footer-widget-<?php echo $i; ?>">
	        	<?php woo_sidebar( 'footer-' . $i ); ?>
			</div>
	
		        <?php } ?>
			<?php } // End WHILE Loop ?>
	
		</section><!-- /#footer-widgets  -->
	<?php } // End IF Statement ?>
		<footer id="footer" class="col-full">
	
			<div id="copyright" class="col-left">
			<p>Elegant-life © 2012 - 2017.</p>
			</div>
	
			<div id="credit" class="col-right">
	        <?php if( isset( $woo_options['woo_footer_right'] ) && $woo_options['woo_footer_right'] == 'true' ) {
	
	        	echo stripslashes( $woo_options['woo_footer_right_text'] );
	
			} else { ?>
				<p><?php _e( 'Powered by', 'woothemes' ); ?> <a href="<?php echo esc_url( 'http://www.wordpress.org' ); ?>">WordPress</a>. <?php _e( 'Designed by', 'woothemes' ); ?> <a href="<?php echo ( isset( $woo_options['woo_footer_aff_link'] ) && ! empty( $woo_options['woo_footer_aff_link'] ) ? esc_url( $woo_options['woo_footer_aff_link'] ) : esc_url( 'http://www.woothemes.com' ) ) ?>"><img src="<?php echo esc_url( get_template_directory_uri().'/images/woothemes.png' ); ?>" width="74" height="19" alt="Woo Themes" /></a></p>
			<?php } ?>
			</div>
			<div class="metrica" style="float:right; display:none">
				<!-- Yandex.Metrika counter -->
				<script type="text/javascript">
				(function (d, w, c) {
				    (w[c] = w[c] || []).push(function() {
				        try {
				            w.yaCounter29825864 = new Ya.Metrika({id:29825864,
				                    webvisor:true,
				                    clickmap:true,
				                    trackLinks:true,
				                    accurateTrackBounce:true});
				        } catch(e) { }
				    });

				    var n = d.getElementsByTagName("script")[0],
				        s = d.createElement("script"),
				        f = function () { n.parentNode.insertBefore(s, n); };
				    s.type = "text/javascript";
				    s.async = true;
				    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

				    if (w.opera == "[object Opera]") {
				        d.addEventListener("DOMContentLoaded", f, false);
				    } else { f(); }
				})(document, window, "yandex_metrika_callbacks");
				</script>
				<noscript><div><img src="//mc.yandex.ru/watch/29825864" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
				<!-- /Yandex.Metrika counter -->



			</div>
	
		</footer><!-- /#footer  -->
	
	</div><!-- / footer-wrap -->
<script crossorigin="anonymous" async type="text/javascript" src="//api.pozvonim.com/widget/callback/v3/2fa79fba544a8ddf9dffb33bd62a7221/connect" id="check-code-pozvonim" charset="UTF-8"></script>


<?php wp_footer(); ?>
<?php woo_foot(); ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-82608473-1', 'auto');
  ga('send', 'pageview');
  ga('require', 'ec');

</script>


</body>
</html>
