<?php

/*
Plugin Name: Slider
Plugin URI: https://huge-it.com/slider
Description: Slider Huge-IT is an awesome WordPress Slider Plugin with many nice features. Just need to install and build slider in a few minutes.
Version: 3.2.3
Author: Huge-IT
Author URI: https://huge-it.com/
License: GNU/GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action('media_buttons_context', 'hugeit_slider_add_my_custom_button' );
function hugeit_slider_add_my_custom_button($context) {
  
  $img = plugins_url( '/images/post.button.png' , __FILE__ );
  $container_id = 'huge_it_slider';

  $title = 'Select Huge IT Slider to insert into post';

  $context .= '<a class="button thickbox" title="Select slider to insert into post"    href="?page=sliders_huge_it_slider&task=add_shortcode_post&TB_iframe=1&width=400&inlineId='.$container_id.'">
		<span class="wp-media-buttons-icon" style="background: url('.$img.'); background-repeat: no-repeat; background-position: left bottom;"></span>
	Add Slider
	</a>';
  
  return $context;
}

add_filter('media_view_strings', 'hugeit_slider_remove_media_tab' );
function hugeit_slider_remove_media_tab($strings) {
	return $strings;
}

add_action('init', 'hugeit_slider_do_output_buffer' );
function hugeit_slider_do_output_buffer() {
	if (isset($_GET['page']) && $_GET['page'] === 'sliders_huge_it_slider') {
		ob_start();
	}
}

$ident = 1;

add_action('admin_head', 'hugeit_slider_huge_it_ajax_func' );
function hugeit_slider_huge_it_ajax_func()
{
    ?>
    <script>
        var huge_it_ajax = '<?php echo admin_url("admin-ajax.php"); ?>';
    </script>
<?php
}

add_shortcode('huge_it_slider', 'huge_it_slider_images_list_shotrcode');
function huge_it_slider_images_list_shotrcode($atts)
{
    extract(shortcode_atts(array(
        'id' => 'no huge_it slider',
    ), $atts));
	hugeit_slider_add_style_to_header($atts['id']);
	add_action('wp_footer', 'hugeit_slider_add_style_to_header' );
        
    wp_register_script( 'bxSlider',plugins_url("js/jquery.bxslider.js", __FILE__) ,array ('jquery'), '1.0.0', true);
	wp_enqueue_script('bxSlider');
	wp_register_script( 'bxSliderSetup',plugins_url("js/bxslider.setup.js", __FILE__) ,array ('jquery'), '1.0.0', true);
	wp_enqueue_script('bxSliderSetup');

	wp_register_style( 'bxSlidercss',plugins_url("style/jquery.bxslider.css", __FILE__));
	wp_enqueue_style('bxSlidercss');
        
    return hugeit_slider_images_list($atts['id']);
}

add_filter('posts_request', 'hugeit_slider_after_search_results' );
function hugeit_slider_after_search_results($query)
{
    global $wpdb;
    if (isset($_REQUEST['s']) && $_REQUEST['s']) {
        $serch_word = sanitize_text_field(($_REQUEST['s']));
		$gen_string_slider_search = hugeit_slider_gen_string_slider_search($serch_word, $wpdb->prefix . 'posts.post_content') . " " . $wpdb->prefix . "posts.post_content";
        $query = str_replace($wpdb->prefix . "posts.post_content" ,$gen_string_slider_search ,$query);
    }
    return $query;
}

function hugeit_slider_gen_string_slider_search($serch_word, $wordpress_query_post) {
    $string_search = '';

    global $wpdb;
    if ($serch_word) {
        $rows_slider = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE (description LIKE %s) OR (name LIKE %s)", '%' . $serch_word . '%', "%" . $serch_word . "%"));

        $count_cat_rows = count($rows_slider);

        for ($i = 0; $i < $count_cat_rows; $i++) {
            $string_search .= $wordpress_query_post . ' LIKE \'%[huge_it_slider id="' . $rows_slider[$i]->id . '" details="1" %\' OR ' . $wordpress_query_post . ' LIKE \'%[huge_it_slider id="' . $rows_slider[$i]->id . '" details="1"%\' OR ';
        }
		
        $rows_slider = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE (name LIKE %s)","'%" . $serch_word . "%'"));
        $count_cat_rows = count($rows_slider);
        for ($i = 0; $i < $count_cat_rows; $i++) {
            $string_search .= $wordpress_query_post . ' LIKE \'%[huge_it_slider id="' . $rows_slider[$i]->id . '" details="0"%\' OR ' . $wordpress_query_post . ' LIKE \'%[huge_it_slider id="' . $rows_slider[$i]->id . '" details="0"%\' OR ';
        }

        $rows_single = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "huge_itslider_images WHERE name LIKE %s","'%" . $serch_word . "%'"));

        $count_sing_rows = count($rows_single);
        if ($count_sing_rows) {
            for ($i = 0; $i < $count_sing_rows; $i++) {
                $string_search .= $wordpress_query_post . ' LIKE \'%[huge_it_slider_Product id="' . $rows_single[$i]->id . '"]%\' OR ';
            }

        }
    }
    return $string_search;
}


function hugeit_slider_images_list($id)
{
    require_once("slider_front_end.html.php");
    require_once("slider_front_end.php");
    if (isset($_GET['product_id'])) {
        if (isset($_GET['view']) && esc_html($_GET['view']) == 'huge_itslider') {
            return hugeit_slider_show_published_images_1($id);
        }
    } else {
        return hugeit_slider_show_published_images_1($id);
    }
}

add_action('admin_menu', 'huge_it_slider_options_panel');
function huge_it_slider_options_panel()
{
    $page_cat = add_menu_page('Theme page title', 'Huge IT Slider', 'delete_pages', 'sliders_huge_it_slider', 'hugeit_slider_page', plugins_url('images/sidebar.icon.png', __FILE__));
    add_submenu_page('sliders_huge_it_slider', 'Sliders', 'Sliders', 'delete_pages', 'sliders_huge_it_slider', 'hugeit_slider_page' );
    $page_option = add_submenu_page('sliders_huge_it_slider', 'Advanced Features PRO', 'Advanced Features PRO', 'manage_options', 'Options_slider_styles', 'hugeit_slider_options_slider_styles' );
	add_submenu_page( 'sliders_huge_it_slider', 'Licensing', 'Licensing', 'manage_options', 'huge_it_slider_Licensing', 'huge_it_slider_Licensing');
	$featured_plugins = add_submenu_page('sliders_huge_it_slider', 'Featured Plugins', 'Featured Plugins', 'manage_options', 'huge_it_slider_featured_plugins', 'huge_it_slider_featured_plugins');
	
	add_action('admin_print_styles-' . $page_cat, 'huge_it_slider_admin_script');
    add_action('admin_print_styles-' . $page_option, 'huge_it_slider_option_admin_script');
    add_action('admin_print_styles-' . $featured_plugins, 'huge_it_slider_featured_plugins_admin_script');

}
function huge_it_slider_Licensing() {
	?>
    <div class="slider-licens">
        <div class="slider-block">
            <div class="licens">
                <a href="http://huge-it.com/slider/">
                    <span class="icon post-slider"></span>
                    <span class="text">Post Slider</span>
                </a>
            </div>
            <div class="licens">
                <a href="http://huge-it.com/slider/">
                    <span class="icon youtube"></span>
                    <span class="text">YouTube Video Support</span>
                </a>
            </div>
            <div class="licens">
                <a href="http://huge-it.com/slider/">
                    <span class="icon vimeo"></span>
                    <span class="text">Vimeo Video Support</span>
                </a>
            </div>
            <div class="licens">
                <a href="http://huge-it.com/slider/">
                    <span class="icon advanced"></span>
                    <span class="text">Advanced Customization</span>
                </a>
            </div>


            <div class="licens">
                <a href="http://huge-it.com/slider/">
                    <span class="icon full-option"></span>
                    <span class="text">Full Option Access</span>
                </a>
            </div>
            <div class="licens">
                <a href="http://huge-it.com/slider/">
                    <span class="icon description"></span>
                    <span class="text">Title & Description Styles</span>
                </a>
            </div>
            <div class="licens">
                <a href="http://huge-it.com/slider/">
                    <span class="icon slider-arrow"></span>
                    <span class="text">Arrow Styles</span>
                </a>
            </div>
            <div class="licens">
                <a href="http://huge-it.com/slider/">
                    <span class="icon design"></span>
                    <span class="text">Design Customization</span>
                </a>
            </div>
        </div>
    </div>
<?php
    wp_enqueue_style('hugeit-slider-licensing-css',plugins_url('style/licensing.css',__FILE__));
}

function huge_it_slider_featured_plugins() {
	?>

    <div class="huge-plugins">
        <div class="plaings-block">
            <div class="plugin">
                <div class="image-block photo-gallery"><a href="http://huge-it.com/wordpress-photo-gallery/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/wordpress-photo-gallery/" target="_blank">Photo Gallery </a></h2>
                    <p>Access advanced options and customization tools of this ultimate gallery, use the best ever functionality and unique features.</p>
                    <a href="http://huge-it.com/wordpress-photo-gallery/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block portfolio-gallery"><a href="http://huge-it.com/portfolio-gallery/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/portfolio-gallery/" target="_blank">Portfolio Gallery</a></h2>
                    <p>Best Portfolio plugin for WordPress with 9 unique views, lightbox options, slideshows, advanced customization solutions.</p>
                    <a href="http://huge-it.com/portfolio-gallery/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block responsive-slider"><a href="http://huge-it.com/wordpress-responsive-slider/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/wordpress-responsive-slider/" target="_blank">Responsive Slider</a></h2>
                    <p>Make stunning sliders using advanced video, YouTube, Vimeo options. Link slides with URL, add text layers, animations and more.</p>
                    <a href="http://huge-it.com/wordpress-responsive-slider/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block forms"><a href="http://huge-it.com/forms/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/forms/" target="_blank">Forms</a></h2>
                    <p>Create different recipients for each form, send newsletters, use ready-to-go fields and more with Forms Pro.</p>
                    <a href="http://huge-it.com/forms/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block gallery"><a href="http://huge-it.com/wordpress-gallery/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/wordpress-gallery/" target="_blank">Gallery</a></h2>
                    <p>Create unlimited stunning galleries of images and videos,  using 8 flexible and customizable views.Show them in a simple gallery forms.</p>
                    <a href="http://huge-it.com/wordpress-gallery/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block slider"><a href="http://huge-it.com/slider/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/slider/" target="_blank">Slider</a></h2>
                    <p>Use million times downloaded slider with theme editor, post feed sliders,video support and advanced options right away.</p>
                    <a href="http://huge-it.com/slider/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block google-map"><a href="http://huge-it.com/google-map/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/google-map/" target="_blank">Google map</a></h2>
                    <p>Make advanced customization, add directions and layers, export maps in CSV file, add links and HTML with Google Maps Pro.</p>
                    <a href="http://huge-it.com/google-map/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block video-gallery"><a href="http://huge-it.com/wordpress-video-gallery/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/wordpress-video-gallery/" target="_blank">Video Gallery</a></h2>
                    <p>Create the best video galleries with this plugin using 7 views, design and lightbox customization, advanced options and more.</p>
                    <a href="http://huge-it.com/wordpress-video-gallery/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block login"><a href="http://huge-it.com/wordpress-login/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/wordpress-login/" target="_blank">Login</a></h2>
                    <p>Add Login and sign up buttons on your site with amazing popup. Customize and style it to your liking, using security options and reCAPTCHA.</p>
                    <a href="http://huge-it.com/wordpress-login/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block catalog"><a href="http://huge-it.com/product-catalog/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/product-catalog/" target="_blank">Catalog</a></h2>
                    <p>Your ultimate store and catalog in 5 stylish views. Create products, design, customize categories, product pages, messages, comments, ratings. </p>
                    <a href="http://huge-it.com/product-catalog/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block lightbox"><a href="http://huge-it.com/lightbox/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/lightbox/" target="_blank">Lightbox</a></h2>
                    <p>Pop up your images and videos in beautiful lightbox, share on social media and secure your images with watermark.</p>
                    <a href="http://huge-it.com/lightbox/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block video-player"><a href="http://huge-it.com/video-player/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/video-player/" target="_blank">Video Player</a></h2>
                    <p>Create and customize your video player using advanced options, select skins and themes for your player.</p>
                    <a href="http://huge-it.com/video-player/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block princing-table"><a href="http://huge-it.com/wordpress-pricing-table-builder/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/wordpress-pricing-table-builder/" target="_blank">Princing Table</a></h2>
                    <p>Sell your products and services using 120 stunning pricing table themes and use more advanced options to design them.</p>
                    <a href="http://huge-it.com/wordpress-pricing-table-builder/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block colorbox"><a href="http://huge-it.com/colorbox/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/colorbox/" target="_blank">Colorbox</a></h2>
                    <p>Add advanced solutions for your Colorbox plugin using customization settings and bring best of popup and lightbox effects.</p>
                    <a href="http://huge-it.com/colorbox/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block share-buttons"><a href="http://huge-it.com/share-buttons/" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/share-buttons/" target="_blank">Share buttons</a></h2>
                    <p>Spread the message of your websites all around social media using 11 beautiful design options and style settings of Share Buttons.</p>
                    <a href="http://huge-it.com/share-buttons/" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
            <div class="plugin">
                <div class="image-block builder"><a href="http://huge-it.com/wordpress-price-table-builder" target="_blank"></a></div>
                <div class="info-block">
                    <h2><a href="http://huge-it.com/wordpress-price-table-builder" target="_blank">Price Table Builder</a></h2>
                    <p>Access full customization list of Pricing Table Builder and change every corner of it, by customizing to your needs.</p>
                    <a href="http://huge-it.com/wordpress-price-table-builder" class="view" target="_blank">View Plugin</a>
                </div>
            </div>
        </div>
    </div>
	<?php
}

function huge_it_slider_admin_script()
{
	wp_enqueue_media();
	wp_enqueue_style( "jquery_ui", plugins_url( "style/jquery-ui.css", __FILE__ ), false );
	if ( ! defined( 'ICL_SITEPRESS_VERSION' ) || ICL_PLUGIN_INACTIVE ) {
		wp_enqueue_script( "jquery" );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script('jquery-ui-sortable');
	}

	wp_enqueue_script( "simple_slider_js", plugins_url( "js/simple-slider.js", __FILE__ ), false );
	wp_enqueue_style( "simple_slider_css", plugins_url( "style/simple-slider.css", __FILE__ ), false );
	wp_enqueue_style( "admin_css", plugins_url( "style/admin.style.css", __FILE__ ), false );
	wp_enqueue_script( "admin_js", plugins_url( "js/admin.js", __FILE__ ), false );
	wp_enqueue_script( 'param_block2', plugins_url( "elements/jscolor/jscolor.js", __FILE__ ) );
}

function huge_it_slider_featured_plugins_admin_script(){
    wp_enqueue_style( "featured_plugins", plugins_url( "style/featured-plugins.css", __FILE__ ), false );
}

function huge_it_slider_option_admin_script() {
	wp_enqueue_script('jquery');

	if ( ! wp_script_is( 'jQuery.ui' ) ) {
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
	}
	wp_enqueue_script( "simple_slider_js", plugins_url( "js/simple-slider.js", __FILE__ ), false );

	wp_enqueue_style( "simple_slider_css", plugins_url( "style/simple-slider.css", __FILE__ ), false );
	wp_enqueue_style( "admin_css", plugins_url( "style/admin.style.css", __FILE__ ), false );
	wp_enqueue_script( "admin_js", plugins_url( "js/admin.js", __FILE__ ), false );
	wp_enqueue_script( 'param_block2', plugins_url( "elements/jscolor/jscolor.js", __FILE__ ) );
}

function hugeit_slider_page() {
    require_once("sliders.php");
    require_once("sliders.html.php");

	if ( ! function_exists( 'hugeit_slider_print_html_nav' ) ) {
		require_once( "slider_function/html_slider_func.php" );
	}

	if ( isset( $_GET["task"] ) ) {
		$task = sanitize_text_field( $_GET["task"] );
	} else {
		$task = '';
	}
	if ( isset( $_GET["id"] ) ) {
		$id = absint( $_GET["id"] );
	} else {
		$id = 0;
	}
	global $wpdb;
	switch ( $task ) {

		case 'add_cat':
			if ( ! isset( $_GET['hugeit_slider_new_slider_nonce'] ) || ! wp_verify_nonce( $_GET['hugeit_slider_new_slider_nonce'], 'new_slider' ) ) {
				die( 'Security check failure.' );
			}
			hugeit_slider_add_slider();
			break;
		case 'add_shortcode_post':
			hugeit_slider_add_shortcode_post();
			break;
		case 'popup_posts':
			if ( $id ) {
				hugeit_slider_popup_posts( $id );
			}
			break;
		case 'popup_video':
			if ( $id ) {
				hugeit_slider_popup_video( $id );
			} else {
				$id = $wpdb->get_var( "SELECT MAX( id ) FROM " . $wpdb->prefix . "huge_itslider_sliders" );
				hugeit_slider_popup_video( $id );
			}
			break;
		case 'edit_cat':
			if ( ! isset( $_GET['hugeit_slider_edit_slide_nonce'] ) || ! wp_verify_nonce( $_GET['hugeit_slider_edit_slide_nonce'], 'edit_slider_' . $id ) ) {
				die( 'Security check failure.' );
			}
			if ( $id ) {
				hugeit_slider_edit_slider( $id );
			} else {
				$id = $wpdb->get_var( "SELECT MAX( id ) FROM " . $wpdb->prefix . "huge_itslider_sliders" );
				hugeit_slider_edit_slider( $id );
			}
			break;

		case 'save':
			if ( $id ) {
				hugeit_slider_apply_cat( $id );
			}
		case 'apply':
			$a = isset($_REQUEST['hugeit_slider_apply_slider']);
			$b = wp_verify_nonce($_REQUEST['hugeit_slider_apply_slider'], 'apply_slider_' . $id);

			if (!$a || !$b) {
				wp_die('Security check failure');
			}

			if ( $id ) {
				hugeit_slider_apply_cat( $id );
				hugeit_slider_edit_slider( $id );
			}
			break;
		case 'remove_cat':
			if ( ! isset( $_GET['hugeit_slider_remove_slide_nonce'] ) || ! wp_verify_nonce( $_GET['hugeit_slider_remove_slide_nonce'], 'delete_slider_' . $id ) ) {
				die( 'Security check failure.' );
			}
			hugeit_slider_remove_slider( $id );
			hugeit_slider_show_slider();
			break;
		default:
			hugeit_slider_show_slider();
			break;
	}
}
function hugeit_slider_add_shortcode_post() {
	?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#hugeitsliderinsert').on('click', function() {

			jQuery('#save-buttom').click();

			var id = jQuery('#huge_it_slider-select option:selected').val();
			if (window.parent.tinyMCE || window.parent.tinyMCE.activeEditor) {
				window.parent.send_to_editor('[huge_it_slider id="' + id + '"]');
			}
			tb_remove();
		})
	});
</script>
<style>
#wpadminbar,.auto-fold #adminmenu, .auto-fold #adminmenu li.menu-top, .auto-fold #adminmenuback, .auto-fold #adminmenuwrap {
	display: none;
}

#wpcontent {
	margin-top: -55px;
}

.wp-core-ui .button {margin:0 0 0 10px !important;}

#slider-unique-options-list li {
	clear:both;
	margin:10px 0 5px 0;
}

#slider-unique-options-list li label {width:130px;}

#save-buttom {display:none;}
 
h3 {
	margin:30px 0 15px 0;
}
</style>
<div class="clear"></div>
<h3>Select the Slider</h3>
<div id="huge_it_slider">
	<?php
	global $wpdb;
	$query    = "SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders";
	$firstrow = $wpdb->get_row( $query );
	if ( isset( $_POST["hugeit_slider_id"] ) ) {
		$id = absint( $_POST["hugeit_slider_id"] );
	} else {
		$id = $firstrow->id;
	}
	if ( isset( $_GET["htslider_id"] ) && absint( $_GET["htslider_id"] ) == absint( $_POST["hugeit_slider_id"] ) ) {
		if ( isset( $_GET["hugeit_save"] ) ) {
			$hugeit_save = absint( $_GET["hugeit_save"] );
			if ( $hugeit_save == 1 ) {

				$post_sl_width            = sanitize_text_field( $_POST["sl_width"] );
				$post_sl_height           = sanitize_text_field( $_POST["sl_height"] );
				$post_pause_on_hover      = sanitize_text_field( $_POST["pause_on_hover"] );
				$post_slider_effects_list = sanitize_text_field( $_POST["slider_effects_list"] );
				$post_sl_pausetime        = sanitize_text_field( $_POST["sl_pausetime"] );
				$post_sl_changespeed      = sanitize_text_field( $_POST["sl_changespeed"] );
				$post_sl_position         = sanitize_text_field( $_POST["sl_position"] );
				$post_sl_loading_icon     = sanitize_text_field( $_POST["sl_loading_icon"] );
				$post_show_thumb          = sanitize_text_field( $_POST["show_thumb"] );

				$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_sliders SET  sl_width = '%s'  WHERE id = %d ", $post_sl_width, $id ) );
				$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_sliders SET  sl_height = '%s'  WHERE id = %d ", $post_sl_height, $id ) );
				$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_sliders SET  pause_on_hover = '%s'  WHERE id = %d ", $post_pause_on_hover, $id ) );
				$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_sliders SET  slider_list_effects_s = '%s'  WHERE id = %d ", $post_slider_effects_list, $id ) );
				$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_sliders SET  description = '%s'  WHERE id = %d ", $post_sl_pausetime, $id ) );
				$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_sliders SET  param = '%s'  WHERE id = %d ", $post_sl_changespeed, $id ) );
				$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_sliders SET  sl_position = '%s'  WHERE id = %d ", $post_sl_position, $id ) );
				$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_sliders SET  sl_loading_icon = '%s' WHERE id = %d ", $post_sl_loading_icon, $id ) );
				$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_itslider_sliders SET  show_thumb = '%s' WHERE id = %d ", $post_show_thumb, $id ) );/*add*/

			}
		}
	}
	// $table_name = $wpdb->prefix."huge_itslider_sliders";
	$shortcodesliders = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders  ORDER BY id ASC" );
	$query            = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE id= %d", $id );
	$row              = $wpdb->get_row( $query );
	$container_id     = 'huge_it_slider'; ?>
<form action="?page=sliders_huge_it_slider&task=add_shortcode_post&TB_iframe=1&width=400&inlineId=<?php echo $container_id; ?>&hugeit_save=1&htslider_id=<?php echo $id; ?>" method="post" name="adminForm" id="adminForm">
	<?php if ( count( $shortcodesliders ) ) {
		echo "<select id='huge_it_slider-select' onchange='this.form.submit()' name='hugeit_slider_id'>";
		foreach ( $shortcodesliders as $shortcodeslider ) {
			$selected = '';
			if ( $shortcodeslider->id == $_POST["hugeit_slider_id"] ) {
				$selected = 'selected="selected"';
			}
			echo "<option " . $selected . " value='" . $shortcodeslider->id . "'>" . $shortcodeslider->name . "</option>";
		}
		echo "</select>";
		echo "<button class='button primary' id='hugeitsliderinsert'>Insert Slider</button>";
	} else {
		echo "No slideshows found", "huge_it_slider";
	}
	$container_id = 'huge_it_slider';
	?>
</div>
	<div id="" class="meta-box-sortables ui-sortable">
		<div id="slider-unique-options" class="">
			<h3 class="hndle"><span>Current Slider Options</span></h3>
			<ul id="slider-unique-options-list">
				<li>
					<label for="sl_width">Width</label>
					<input type="text" name="sl_width" id="sl_width" value="<?php echo $row->sl_width; ?>"
					       class="text_area"/>
				</li>
				<li>
					<label for="sl_height">Height</label>
					<input type="text" name="sl_height" id="sl_height" value="<?php echo $row->sl_height; ?>"
					       class="text_area"/>
				</li>
				<li>
					<label for="pause_on_hover">Pause on Hover</label>
					<input type="hidden" value="off" name="pause_on_hover"/>
					<input type="checkbox" name="pause_on_hover" value="on"
					       id="pause_on_hover" <?php if ( $row->pause_on_hover == 'on' ) { echo 'checked="checked"';} ?> />
				</li>
				<li>
					<label for="slider_effects_list">Effects</label>
					<select name="slider_effects_list" id="slider_effects_list">
						<option <?php if ( $row->slider_list_effects_s == 'none' ) {echo 'selected';} ?> value="none">None</option>
						<option <?php if ( $row->slider_list_effects_s == 'cubeH' ) {echo 'selected';} ?> value="cubeH">Cube Horizontal</option>
						<option <?php if ( $row->slider_list_effects_s == 'cubeV' ) {echo 'selected';} ?> value="cubeV">Cube Vertical</option>
						<option <?php if ( $row->slider_list_effects_s == 'fade' ) {echo 'selected';} ?> value="fade">Fade</option>
						<option <?php if ( $row->slider_list_effects_s == 'sliceH' ) {echo 'selected';} ?> value="sliceH">Slice Horizontal</option>
						<option <?php if ( $row->slider_list_effects_s == 'sliceV' ) {echo 'selected';} ?> value="sliceV">Slice Vertical</option>
						<option <?php if ( $row->slider_list_effects_s == 'slideH' ) {echo 'selected';} ?> value="slideH">Slide Horizontal</option>
						<option <?php if ( $row->slider_list_effects_s == 'slideV' ) {echo 'selected';} ?> value="slideV">Slide Vertical</option>
						<option <?php if ( $row->slider_list_effects_s == 'scaleOut' ) {echo 'selected';} ?> value="scaleOut">Scale Out</option>
						<option <?php if ( $row->slider_list_effects_s == 'scaleIn' ) {echo 'selected';} ?> value="scaleIn">Scale In</option>
						<option <?php if ( $row->slider_list_effects_s == 'blockScale' ) {echo 'selected';} ?> value="blockScale">Block Scale</option>
						<option <?php if ( $row->slider_list_effects_s == 'kaleidoscope' ) {echo 'selected';} ?> value="kaleidoscope">Kaleidoscope</option>
						<option <?php if ( $row->slider_list_effects_s == 'fan' ) {echo 'selected';} ?> value="fan">Fan</option>
						<option <?php if ( $row->slider_list_effects_s == 'blindH' ) {echo 'selected';} ?> value="blindH">Blind Horizontal</option>
						<option <?php if ( $row->slider_list_effects_s == 'blindV' ) {echo 'selected';} ?> value="blindV">Blind Vertical</option>
						<option <?php if ( $row->slider_list_effects_s == 'random' ) {echo 'selected';} ?> value="random">Random</option>
					</select>
				</li>

				<li>
					<label for="sl_pausetime">Pause Time</label>
					<input type="text" name="sl_pausetime" id="sl_pausetime" value="<?php echo $row->description; ?>"
					       class="text_area"/>
				</li>
				<li>
					<label for="sl_changespeed">Change Speed</label>
					<input type="text" name="sl_changespeed" id="sl_changespeed" value="<?php echo $row->param; ?>"
					       class="text_area"/>
				</li>
				<li>
					<label for="slider_position">Slider Position</label>
					<select name="sl_position" id="slider_position">
						<option <?php if ( $row->sl_position == 'left' ) {echo 'selected';} ?> value="left">Left</option>
						<option <?php if ( $row->sl_position == 'right' ) {echo 'selected';} ?> value="right">Right</option>
						<option <?php if ( $row->sl_position == 'center' ) {echo 'selected';} ?> value="center">Center</option>
					</select>
				</li>
				<li>
					<label for="sl_loading_icon">Loading Icon</label>
					<select id="sl_loading_icon" name="sl_loading_icon">
						<option <?php if ( $row->sl_loading_icon == 'on' ) {echo 'selected';} ?> value="on">On</option>
						<option <?php if ( $row->sl_loading_icon == 'off' ) {echo 'selected';} ?> value="off">Off</option>
					</select>
				</li>
				<li>
					<label for="show_thumb">Navigate By</label>
					<input type="hidden" value="off" name="show_thumb"/>
					<select id="show_thumb" name="show_thumb">
						<option <?php if ( $row->show_thumb == 'dotstop' ) {echo 'selected';} ?> value="dotstop">Dots</option>
						<option <?php if ( $row->show_thumb == 'thumbnails' ) {echo 'selected';} ?> value="thumbnails">Thumbnails</option>
						<option <?php if ( $row->show_thumb == 'nonav' ) {echo 'selected';} ?> value="nonav">No Navigation</option>
					</select>
				</li>

			</ul>
			<input type="submit" value="Save Slider" id="save-buttom" class="button button-primary button-large">
		</div>
	</div>
	</form>
<?php
}
function hugeit_slider_options_slider_styles() {
    require_once("slider_Options.php");
    require_once("slider_Options.html.php");

    hugeit_slider_show_styles();
}

/**
 * Huge IT Widget
 */
class Hugeit_Slider_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'hugeit_slider_widget',
			'Huge IT Slider',
			array( 'description' => __( 'Huge IT Slider', 'huge_it_slider' ), ) 
		);
	}

	public function widget( $args, $instance ) {

		extract($args);

		if (isset($instance['slider_id'])) {
			$slider_id = $instance['slider_id'];

			$title = apply_filters( 'widget_title', $instance['title'] );
/**
 * @var $before_widget
 * @var $after_title
 * @var $before_title
 * @var $after_widget
 */
			echo $before_widget;
			if ( ! empty( $title ) )
				echo $before_title . $title . $after_title;

			echo do_shortcode("[huge_it_slider id={$slider_id}]");
			echo $after_widget;
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['slider_id'] = strip_tags( $new_instance['slider_id'] );
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	public function form( $instance ) {
		$selected_slider = 0;
		$title = "";
		$sliders = false;

		if (isset($instance['slider_id'])) {
			$selected_slider = $instance['slider_id'];
		}

		if (isset($instance['title'])) {
			$title = $instance['title'];
		}
		?>
		<p>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<label for="<?php echo $this->get_field_id('slider_id'); ?>"><?php _e('Select Slider:', 'huge_it_slider'); ?></label>
			<select id="<?php echo $this->get_field_id('slider_id'); ?>" name="<?php echo $this->get_field_name('slider_id'); ?>">

			<?php
			global $wpdb;
			$query="SELECT * FROM ".$wpdb->prefix."huge_itslider_sliders ";
			$rowwidget=$wpdb->get_results($query);
			foreach($rowwidget as $rowwidgetecho) : ?>
				<option <?php if($rowwidgetecho->id == $instance['slider_id']){ echo 'selected'; } ?> value="<?php echo $rowwidgetecho->id; ?>"><?php echo $rowwidgetecho->name; ?></option>
			<?php endforeach; ?>
			</select>
		</p>

		<?php 
	}
}
add_action('widgets_init', 'hugeit_slider_register_widget' );
function hugeit_slider_register_widget() {
    register_widget('Hugeit_Slider_Widget');
}

function hugeit_slider_add_style_to_header( $id ) {
	global $wpdb;
	$query     = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_images WHERE slider_id = '%d' ORDER BY ordering ASC", $id );
	$images    = $wpdb->get_results( $query );
	$query     = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_itslider_sliders WHERE id = '%d' ORDER BY id ASC", $id );
	$slider    = $wpdb->get_results( $query );
	$query     = "SELECT * FROM " . $wpdb->prefix . "huge_itslider_params";
	$rowspar   = $wpdb->get_results( $query );
	$paramssld = array();
	foreach ( $rowspar as $rowpar ) {
		$key               = $rowpar->name;
		$value             = $rowpar->value;
		$paramssld[ $key ] = $value;
	}
	$sliderID = isset( $slider[0]->id ) ? $slider[0]->id : '';
	$slidertitle = isset( $slider[0]->name ) ? $slider[0]->name : '';
	if ( isset( $slider[0]->sl_height ) ) {
		$sliderheight = $slider[0]->sl_height;
	} else {
		$sliderheight = '';
	}
	if ( isset( $slider[0]->sl_width ) ) {
		$sliderwidth = $slider[0]->sl_width;
	} else {
		$sliderwidth = '';
	}
	if ( isset( $slider[0]->slider_list_effects_s ) ) {
		$slidereffect = $slider[0]->slider_list_effects_s;
	} else {
		$slidereffect = '';
	}
	if ( isset( $slidepausetime ) ) {
		$slidepausetime = ( $slider[0]->description + $slider[0]->param );
	} else {
		$slidepausetime = '';
	}
	if ( isset( $slider[0]->pause_on_hover ) ) {
		$sliderpauseonhover = $slider[0]->pause_on_hover;
	} else {
		$sliderpauseonhover = '';
	}
	if ( isset( $slider[0]->sl_position ) ) {
		$sliderposition = $slider[0]->sl_position;
	} else {
		$sliderposition = '';
	}
	if ( isset( $slider[0]->param ) ) {
		$slidechangespeed = $slider[0]->param;
	} else {
		$slidechangespeed = '';
	}
	if ( isset( $slider[0]->sl_loading_icon ) ) {
		$sliderloadingicon = $slider[0]->sl_loading_icon;
	} else {
		$sliderloadingicon = '';
	}
	if ( isset( $slider[0]->show_thumb ) ) {
		$sliderthumbslider = $slider[0]->show_thumb;
	} else {
		$sliderthumbslider = '';
	}
	$sliderBorderWidth = $paramssld['slider_slideshow_border_size'];

	if ( $sliderthumbslider == 'thumbnails' ) {
		$thumbHeight = $paramssld['slider_thumb_height'] + $sliderBorderWidth;
	} else {
		$thumbHeight = 0;
	}

	$slideshow_title_position = explode('-', trim($paramssld['slider_title_position']));
	$slideshow_description_position = explode('-', trim($paramssld['slider_description_position']));
	?>

	<style>
		/***<add>***/
		#huge_it_loading_image_<?php echo $sliderID; ?> {
			height:<?php echo $sliderheight; ?>px;
			width:<?php  echo $sliderwidth; ?>px;
			display: table-cell;
			text-align: center;
			vertical-align: middle;
		}
		#huge_it_loading_image_<?php echo $sliderID; ?>.display {
			display: table-cell;
		}
		#huge_it_loading_image_<?php echo $sliderID; ?>.nodisplay {
			display: none;
		}
		#huge_it_loading_image_<?php echo $sliderID; ?> img {
			margin: auto 0;
			width: 20% !important;

		}

		.huge_it_slideshow_image_wrap_<?php echo $sliderID; ?> {
			height:<?php echo $sliderheight - 2*$sliderBorderWidth+$thumbHeight; ?>px;
			width:<?php  echo $sliderwidth - 2*$sliderBorderWidth; ?>px;
			max-width: calc(100% - <?php echo 2*$sliderBorderWidth; ?>px);
			position:relative;
			display: block;
			text-align: center;
			/*HEIGHT FROM HEADER.PHP*/
			clear:both;

		<?php
		if ($sliderposition!="left") {
			if($sliderposition=="right") {
				$position='float:right;margin: 20px';
			} else {
				$position='float:none; margin:0px auto;';
			}
		} else {
			$position='float:left;margin: 20px';
		}
		echo $position; ?>
			border-style: solid;
			border-left: 0 !important;
			border-right: 0 !important;
		<?php if($sliderloadingicon == 'off') {echo 'opacity:0';}?>
		}

		.huge_it_slideshow_image_wrap1_<?php echo $sliderID; ?>.display {
			width: 100%;
			height: 100%;
		}

		.huge_it_slideshow_image_wrap1_<?php echo $sliderID; ?>.display {
			display: block;
		}

		.huge_it_slideshow_image_wrap1_<?php echo $sliderID; ?>.nodisplay {
			opacity: 0;
		}

		.huge_it_slideshow_image_wrap_<?php echo $sliderID; ?> * {
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			-webkit-box-sizing: border-box;
		}

		.huge_it_slideshow_image_<?php echo $sliderID; ?> {
		<?php if($paramssld['slider_crop_image'] =="resize"){?> width: 100%;
			height: 100%;
		<?php } else{?> height: auto;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
		<?php }?> max-width: 100%;
			max-height: 100%;

		}
		.huge_it_slideshow_image_wrap1_<?php echo $sliderID; ?>{
			height:<?php echo $sliderheight  - 2*$sliderBorderWidth; ?>px;
			width:<?php  echo $sliderwidth - 2*$sliderBorderWidth; ?>px;
			max-width: 100%;
		}
		#huge_it_slideshow_left_<?php echo $sliderID; ?>,
		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			cursor: pointer;
			display:none;
			height: 100%;
			outline: medium none;
			position: absolute;
			z-index: 13;
		<?php if($sliderthumbslider == 'thumbnails'){?>
			top:calc(50% - <?php echo $paramssld['slider_thumb_height']/2+$paramssld['slider_slideshow_border_size']; ?>px);
		<?php }else{?>
			margin-top: 0 !important;
			top: 50%;
			transform: translateY(-50%);
		<?php } ?>
		}

		#huge_it_slideshow_left-ico_<?php echo $sliderID; ?>,
		#huge_it_slideshow_right-ico_<?php echo $sliderID; ?> {
			z-index: 13;
			-moz-box-sizing: content-box;
			box-sizing: content-box;
			cursor: pointer;
			display: table;
			left: -9999px;
			line-height: 0;
			margin-top: -15px;
			position: absolute;
			top: 50%;
			/*z-index: 10135;*/
		}
		#huge_it_slideshow_left-ico_<?php echo $sliderID; ?>:hover,
		#huge_it_slideshow_right-ico_<?php echo $sliderID; ?>:hover {
			cursor: pointer;
		}

		.huge_it_slideshow_image_container_<?php echo $sliderID; ?> {
			display: table;
			position: relative;
			top:0;
			left:0;
			text-align: center;
			vertical-align: middle;
			width:100%;
			overflow:hidden;
			height: 100%;
		}

		.huge_it_slideshow_title_text_<?php echo $sliderID; ?> {
			text-decoration: none;
			position: absolute;
			z-index: 11;
			display: inline-block;
		<?php  if($paramssld['slider_title_has_margin']=='on'){
				$slider_title_width=($paramssld['slider_title_width']-6);
				$slider_title_height=($paramssld['slider_title_height']-6);
				$slider_title_margin="3";
			}else{
				$slider_title_width=($paramssld['slider_title_width']);
				$slider_title_height=($paramssld['slider_title_height']);
				$slider_title_margin="0";
			}  ?> width: <?php echo $slider_title_width; ?>%;
			/*height:
		<?php echo $slider_title_height; ?> %;*/

		<?php
			if($slideshow_title_position[0]=="left"){echo 'left:'.$slider_title_margin.'%;';}
			elseif($slideshow_title_position[0]=="center"){echo 'left:50%;';}
			elseif($slideshow_title_position[0]=="right"){echo 'right:'.$slider_title_margin.'%;';}

			if($slideshow_title_position[1]=="top"){echo 'top:'.$slider_title_margin.'%;';}
			elseif($slideshow_title_position[1]=="middle"){echo 'top:50%;';}
			elseif($slideshow_title_position[1]=="bottom"){echo 'bottom:'.$slider_title_margin.'%;';}
		 ?> padding: 2%;
			text-align: <?php echo $paramssld['slider_title_text_align']; ?>;
			font-weight: bold;
			color: #<?php echo $paramssld['slider_title_color']; ?>;

			background: <?php
				list($r,$g,$b) = array_map('hexdec',str_split($paramssld['slider_title_background_color'],2));
				$titleopacity=$paramssld["slider_title_background_transparency"]/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
		?>;
			border-style: solid;
			font-size: <?php echo $paramssld['slider_title_font_size']; ?>px;
			border-width: <?php echo $paramssld['slider_title_border_size']; ?>px;
			border-color: #<?php echo $paramssld['slider_title_border_color']; ?>;
			border-radius: <?php echo $paramssld['slider_title_border_radius']; ?>px;
		}

		.huge_it_slideshow_description_text_<?php echo $sliderID; ?> {
			text-decoration: none;
			position: absolute;
			z-index: 11;
			border-style:solid;
			display: inline-block;
		<?php  if($paramssld['slider_description_has_margin']=='on'){
				$slider_description_width=($paramssld['slider_description_width']-6);
				$slider_description_height=($paramssld['slider_description_height']-6);
				$slider_description_margin="3";
			}else{
				$slider_description_width=($paramssld['slider_description_width']);
				$slider_descriptione_height=($paramssld['slider_description_height']);
				$slider_description_margin="0";
			}  ?>

			width:<?php echo $slider_description_width; ?>%;
			/*height:<?php echo $slider_description_height; ?>%;*/
		<?php
			if($slideshow_description_position[0]=="left"){echo 'left:'.$slider_description_margin.'%;';}
			elseif($slideshow_description_position[0]=="center"){echo 'left:50%;';}
			elseif($slideshow_description_position[0]=="right"){echo 'right:'.$slider_description_margin.'%;';}

			if($slideshow_description_position[1]=="top"){echo 'top:'.$slider_description_margin.'%;';}
			elseif($slideshow_description_position[1]=="middle"){echo 'top:50%;';}
			elseif($slideshow_description_position[1]=="bottom"){echo 'bottom:'.$slider_description_margin.'%;';}
		 ?>
			padding:3%;
			text-align:<?php echo $paramssld['slider_description_text_align']; ?>;
			color:#<?php echo $paramssld['slider_description_color']; ?>;

			background:<?php
			list($r,$g,$b) = array_map('hexdec',str_split($paramssld['slider_description_background_color'],2));
			$descriptionopacity=$paramssld["slider_description_background_transparency"]/100;
			echo 'rgba('.$r.','.$g.','.$b.','.$descriptionopacity.') !important';
		?>;
			border-style:solid;
			font-size:<?php echo $paramssld['slider_description_font_size']; ?>px;
			border-width:<?php echo $paramssld['slider_description_border_size']; ?>px;
			border-color:#<?php echo $paramssld['slider_description_border_color']; ?>;
			border-radius:<?php echo $paramssld['slider_description_border_radius']; ?>px;
		}

		.huge_it_slideshow_title_text_<?php echo $sliderID; ?>.none, .huge_it_slideshow_description_text_<?php echo $sliderID; ?>.none,
		.huge_it_slideshow_title_text_<?php echo $sliderID; ?>.hidden, .huge_it_slideshow_description_text_<?php echo $sliderID; ?>.hidden	   {display:none;}

		.huge_it_slideshow_title_text_<?php echo $sliderID; ?> h1, .huge_it_slideshow_description_text_<?php echo $sliderID; ?> h1,
		.huge_it_slideshow_title_text_<?php echo $sliderID; ?> h2, .huge_it_slideshow_title_text_<?php echo $sliderID; ?> h2,
		.huge_it_slideshow_title_text_<?php echo $sliderID; ?> h3, .huge_it_slideshow_title_text_<?php echo $sliderID; ?> h3,
		.huge_it_slideshow_title_text_<?php echo $sliderID; ?> h4, .huge_it_slideshow_title_text_<?php echo $sliderID; ?> h4,
		.huge_it_slideshow_title_text_<?php echo $sliderID; ?> p, .huge_it_slideshow_title_text_<?php echo $sliderID; ?> p,
		.huge_it_slideshow_title_text_<?php echo $sliderID; ?> strong,  .huge_it_slideshow_title_text_<?php echo $sliderID; ?> strong,
		.huge_it_slideshow_title_text_<?php echo $sliderID; ?> span, .huge_it_slideshow_title_text_<?php echo $sliderID; ?> span,
		.huge_it_slideshow_title_text_<?php echo $sliderID; ?> ul, .huge_it_slideshow_title_text_<?php echo $sliderID; ?> ul,
		.huge_it_slideshow_title_text_<?php echo $sliderID; ?> li, .huge_it_slideshow_title_text_<?php echo $sliderID; ?> li {
			padding:2px;
			margin:0;
		}

		.huge_it_slide_container_<?php echo $sliderID; ?> {
			display: table-cell;
			margin: 0 auto;
			position: relative;
			vertical-align: middle;
			width:100%;
			height:100%;
			_width: inherit;
			_height: inherit;
		}
		.huge_it_slide_bg_<?php echo $sliderID; ?> {
			margin: 0 auto;
			width:100%;
			height:100%;
			_width: inherit;
			_height: inherit;
		}
		.huge_it_slide_bg_<?php echo $sliderID; ?> li img{
			position: absolute;
		<?php if($paramssld['slider_crop_image'] != 'crop') { ?>
			/*top: -<?php echo $paramssld['slider_slideshow_border_size']; ?>px !important;
                    left: -<?php echo $paramssld['slider_slideshow_border_size']; ?>px !important;*/
			left:0;
		<?php }?>
			height: 100%;
		}
		.huge_it_slide_bg_<?php echo $sliderID; ?> li iframe{
			width: 100%;
			height: 100%;
		}
		.huge_it_slider_<?php echo $sliderID; ?> {
			width:100%;
			height:100%;
			display:table !important;
			padding:0 !important;
			margin:0 !important;

		}
		.huge_it_slideshow_image_item_<?php echo $sliderID; ?> {
			width:100%;
			height:100%;
			_width: inherit;
			_height: inherit;
			display: table-cell;
			filter: Alpha(opacity=100);
			opacity: 1;
			position: absolute !important;
			top:0 !important;
			left:0 !important;
			vertical-align: middle;
			z-index: 1;
			margin:0 !important;
			padding:0 !important;
			overflow: hidden !important;
			border-radius: <?php echo $paramssld['slider_slideshow_border_radius']; ?>px !important;
		}
		.huge_it_slideshow_image_second_item_<?php echo $sliderID; ?> {
			width:100%;
			height:100%;
			_width: inherit;
			_height: inherit;
			display: table-cell;
			filter: Alpha(opacity=0);
			opacity: 0;
			position: absolute !important;
			top:0 !important;
			left:0 !important;
			vertical-align: middle;
			overflow:hidden;
			margin:0 !important;
			visibility:visible !important;
			padding:0 !important;
			border-radius: <?php echo $paramssld['slider_slideshow_border_radius']; ?>px !important;
		}
		.huge_it_slideshow_image_second_item_<?php echo $sliderID; ?> a, .huge_it_slideshow_image_item_<?php echo $sliderID; ?> a {
			display:block;
			width:100%;
			height:100%;
		}

		.huge_it_grid_<?php echo $sliderID; ?> {
			display: none;
			height: 100%;
			overflow: hidden;
			position: absolute;
			width: 100%;
		}
		.huge_it_gridlet_<?php echo $sliderID; ?> {
			opacity: 1;
			filter: Alpha(opacity=100);
			position: absolute;
		}
		.huge_it_slideshow_dots_container_<?php echo $sliderID; ?> {
			display: table;
			position: absolute;
			width:100% !important;
			height:100% !important;
		}
		.huge_it_slideshow_dots_thumbnails_<?php echo $sliderID; ?> {
			margin: 0 auto;
			overflow: hidden;
			position: absolute;
			width:100%;
			height:30px;
		}

		.huge_it_slideshow_dots_<?php echo $sliderID; ?> {
			display: inline-block;
			position: relative;
			cursor: pointer;
			box-shadow: 1px 1px 1px rgba(0,0,0,0.1) inset, 1px 1px 1px rgba(255,255,255,0.1);
			width:10px;
			height: 10px;
			border-radius: 10px;
			background: #00f;
			margin: 10px;
			overflow: hidden;
			z-index: 17;
		}

		.huge_it_slideshow_dots_active_<?php echo $sliderID; ?> {
			opacity: 1;
			filter: Alpha(opacity=100);
		}
		.huge_it_slideshow_dots_deactive_<?php echo $sliderID; ?> {

		}

		.huge_it_slideshow_image_wrap_<?php echo $sliderID; ?> {
			background:#<?php echo $paramssld['slider_slider_background_color']; ?>;
			border-width:<?php echo $paramssld['slider_slideshow_border_size']; ?>px;
			border-color:#<?php echo $paramssld['slider_slideshow_border_color']; ?>;
			border-radius:<?php echo $paramssld['slider_slideshow_border_radius']; ?>px;
		}
		.huge_it_slideshow_image_wrap_<?php echo $sliderID; ?>.nocolor {
			background: transparent;
		}

		.huge_it_slideshow_dots_thumbnails_<?php echo $sliderID; ?> {
		<?php if($sliderthumbslider == "dotstop" && $sliderthumbslider != "thumbnails" && $paramssld['slider_dots_position_new']=='dotsbottom'){?>
			bottom:0;
		<?php }else if($sliderthumbslider == "thumbnails" || $sliderthumbslider=="nonav"){?>
			display:none;
		<?php
		}else if($sliderthumbslider == "dotstop"){ ?>
			top:0; <?php } ?>
		}

		.huge_it_slideshow_dots_<?php echo $sliderID; ?> {
			background:#<?php echo $paramssld['slider_dots_color']; ?>;
		}

		.huge_it_slideshow_dots_active_<?php echo $sliderID; ?> {
			background:#<?php echo $paramssld['slider_active_dot_color']; ?>;
		}

		<?php
		require_once(dirname(__FILE__) . '/slider_front_end.html.php');
		if (isset($GLOBALS['thumbnail_width'])) {
			$width_huge=$GLOBALS['thumbnail_width'];
		}else{
			$width_huge='';
		}
		?>
		/*//////////////////////slider thunbnail styles start///////////////////////////*/

		.bx-viewport {
			height: <?php echo $paramssld['slider_thumb_height']; ?>px !important;
			-webkit-transform: translatez(0);
		}
		.entry-content .huge_it_slideshow_image_wrap_<?php echo $sliderID; ?> a{
			border-bottom: none !important;
		}
		.entry-content .huge_it_slideshow_image_wrap_<?php echo $sliderID; ?> li{
			margin:0 !important;
			padding: 0 !important;
		}
		.entry-content .huge_it_slideshow_image_wrap_<?php echo $sliderID; ?> ul{
			list-style-type:none !important;
			margin: 0 !important;
			padding: 0 !important;
		}
		.bx-wrapper {
			position: relative;
			margin: 0 auto 0 auto;
			padding: 0;
			max-width: <?php echo $width_huge; ?>px !important;
			*zoom: 1;
			-ms-touch-action: pan-y;
			touch-action: pan-y;
		}
		.huge_it_slideshow_thumbs_container_<?php echo $sliderID; ?>{
		<?php if($sliderthumbslider == "dotstop" || $sliderthumbslider == "dotsbottom" || $sliderthumbslider == "nonav"){?>
			display: none;
		<?php }?>
		}
		.huge_it_slideshow_thumbs_<?php echo $sliderID; ?>{

			margin: 0;
		}
		.huge_it_slideshow_thumbs_<?php echo $sliderID; ?> li{
			display: inline-block;

			height: <?php echo $paramssld['slider_thumb_height']; ?>px ;

		}
		.huge_it_slideshow_thumbnails_<?php echo $sliderID; ?> {
			display: inline-block;
			position: relative;
			cursor: pointer;
			background: #<?php echo $paramssld['slider_thumb_back_color']; ?>;
			z-index: 17;
			height: <?php echo $paramssld['slider_thumb_height']; ?>px;
		}
		.sl_thumb_img{
			width: 100% !important;
			height: 100% !important;
			display: block;
			margin: 0 auto;
		}
		.sl_thumb_img2{
			height: 100% !important;
			display: block;
			margin: 0 auto;
		}
		.trans_back{
			width: 100%;
			height: 100%;
			top:0;
			position: absolute;
			background:<?php
				list($ri,$gi,$bi) = array_map('hexdec',str_split($paramssld['slider_thumb_passive_color'],2));
				$titleopacity2=$paramssld["slider_thumb_passive_color_trans"]/100;
				echo 'rgba('.$ri.','.$gi.','.$bi.','.$titleopacity2.')';
		?>;
			transition: 0.3s ease;
		}
		.trans_back:hover{
			background:none !important;
		}
		.play-icon.youtube {background:url(<?php echo plugins_url("images/play.youtube.png", __FILE__)?>) center center no-repeat;
			width: 100%;
			height: 100%;
			top:0;
			position: absolute;}

		.play-icon.vimeo {background:url(<?php echo plugins_url("images/play.vimeo.png", __FILE__)?>) center center no-repeat;
			width: 100%;
			height: 100%;
			top:0;
			position: absolute;
		}
		.bx-wrapper {

			border: 0 solid #fff;
			background: #fff;
		}

		/*////////////slider thunbnail styles end//////////////*/

		<?php

		$arrowfolder=plugins_url('slider-image/Front_images/arrows');
		switch ($paramssld['slider_navigation_type']) {
			case 1:
				?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-21px;
			height:43px;
			width:29px;
			background:url(<?php echo $arrowfolder;?>/arrows.simple.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-21px;
			height:43px;
			width:29px;
			background:url(<?php echo $arrowfolder;?>/arrows.simple.png) right top no-repeat;
			background-size: 200%;

		}
		<?php
		break;
	case 2:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-25px;
			height:50px;
			width:50px;
			background:url(<?php echo $arrowfolder;?>/arrows.circle.shadow.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-25px;
			height:50px;
			width:50px;
			background:url(<?php echo $arrowfolder;?>/arrows.circle.shadow.png) right top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_left_<?php echo $sliderID; ?>:hover {
			background-position:left -50px;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?>:hover {
			background-position:right -50px;
		}
		<?php
		break;
	case 3:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-22px;
			height:44px;
			width:44px;
			background:url(<?php echo $arrowfolder;?>/arrows.circle.simple.dark.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-22px;
			height:44px;
			width:44px;
			background:url(<?php echo $arrowfolder;?>/arrows.circle.simple.dark.png) right top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_left_<?php echo $sliderID; ?>:hover {
			background-position:left -44px;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?>:hover {
			background-position:right -44px;
		}
		<?php
		break;
	case 4:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-33px;
			height:65px;
			width:59px;
			background:url(<?php echo $arrowfolder;?>/arrows.cube.dark.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-33px;
			height:65px;
			width:59px;
			background:url(<?php echo $arrowfolder;?>/arrows.cube.dark.png) right top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_left_<?php echo $sliderID; ?>:hover {
			background-position:left -66px;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?>:hover {
			background-position:right -66px;
		}
		<?php
		break;
	case 5:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-18px;
			height:37px;
			width:40px;
			background:url(<?php echo $arrowfolder;?>/arrows.light.blue.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-18px;
			height:37px;
			width:40px;
			background:url(<?php echo $arrowfolder;?>/arrows.light.blue.png) right top no-repeat;
			background-size: 200%;
		}

		<?php
		break;
	case 6:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-25px;
			height:50px;
			width:50px;
			background:url(<?php echo $arrowfolder;?>/arrows.light.cube.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-25px;
			height:50px;
			width:50px;
			background:url(<?php echo $arrowfolder;?>/arrows.light.cube.png) right top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_left_<?php echo $sliderID; ?>:hover {
			background-position:left -50px;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?>:hover {
			background-position:right -50px;
		}
		<?php
		break;
	case 7:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			right:0;
			margin-top:-19px;
			height:38px;
			width:38px;
			background:url(<?php echo $arrowfolder;?>/arrows.light.transparent.circle.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-19px;
			height:38px;
			width:38px;
			background:url(<?php echo $arrowfolder;?>/arrows.light.transparent.circle.png) right top no-repeat;
			background-size: 200%;
		}
		<?php
		break;
	case 8:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-22px;
			height:45px;
			width:45px;
			background:url(<?php echo $arrowfolder;?>/arrows.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-22px;
			height:45px;
			width:45px;
			background:url(<?php echo $arrowfolder;?>/arrows.png) right top no-repeat;
			background-size: 200%;
		}
		<?php
		break;
	case 9:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-22px;
			height:45px;
			width:45px;
			background:url(<?php echo $arrowfolder;?>/arrows.circle.blue.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-22px;
			height:45px;
			width:45px;
			background:url(<?php echo $arrowfolder;?>/arrows.circle.blue.png) right top no-repeat;
			background-size: 200%;
		}
		<?php
		break;
	case 10:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-24px;
			height:48px;
			width:48px;
			background:url(<?php echo $arrowfolder;?>/arrows.circle.green.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-24px;
			height:48px;
			width:48px;
			background:url(<?php echo $arrowfolder;?>/arrows.circle.green.png) right top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_left_<?php echo $sliderID; ?>:hover {
			background-position:left -48px;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?>:hover {
			background-position:right -48px;
		}
		<?php
		break;
	case 11:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-29px;
			height:58px;
			width:55px;
			background:url(<?php echo $arrowfolder;?>/arrows.blue.retro.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-29px;
			height:58px;
			width:55px;
			background:url(<?php echo $arrowfolder;?>/arrows.blue.retro.png) right top no-repeat;
			background-size: 200%;
		}
		<?php
		break;
	case 12:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-37px;
			height:74px;
			width:74px;
			background:url(<?php echo $arrowfolder;?>/arrows.green.retro.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-37px;
			height:74px;
			width:74px;
			background:url(<?php echo $arrowfolder;?>/arrows.green.retro.png) right top no-repeat;
			background-size: 200%;
		}
		<?php
		break;
	case 13:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-16px;
			height:33px;
			width:33px;
			background:url(<?php echo $arrowfolder;?>/arrows.red.circle.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-16px;
			height:33px;
			width:33px;
			background:url(<?php echo $arrowfolder;?>/arrows.red.circle.png) right top no-repeat;
			background-size: 200%;
		}
		<?php
		break;
	case 14:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-51px;
			height:102px;
			width:52px;
			background:url(<?php echo $arrowfolder;?>/arrows.triangle.white.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-51px;
			height:102px;
			width:52px;
			background:url(<?php echo $arrowfolder;?>/arrows.triangle.white.png) right top no-repeat;
			background-size: 200%;
		}
		<?php
		break;
	case 15:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:0;
			margin-top:-19px;
			height:39px;
			width:70px;
			background:url(<?php echo $arrowfolder;?>/arrows.ancient.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:0;
			margin-top:-19px;
			height:39px;
			width:70px;
			background:url(<?php echo $arrowfolder;?>/arrows.ancient.png) right top no-repeat;
			background-size: 200%;
		}
		<?php
		break;
	case 16:
		?>
		#huge_it_slideshow_left_<?php echo $sliderID; ?> {
			left:-21px;
			margin-top:-20px;
			height:40px;
			width:37px;
			background:url(<?php echo $arrowfolder;?>/arrows.black.out.png) left  top no-repeat;
			background-size: 200%;
		}

		#huge_it_slideshow_right_<?php echo $sliderID; ?> {
			right:-21px;
			margin-top:-20px;
			height:40px;
			width:37px;
			background:url(<?php echo $arrowfolder;?>/arrows.black.out.png) right top no-repeat;
			background-size: 200%;
		}
		<?php
		break;
}
		/***<For Responsive slider>***/
		if((int)$sliderwidth != 0){
			$titleValue = (int)$paramssld['slider_title_font_size']/(int)$sliderwidth;
			$descValue = (int)$paramssld['slider_description_font_size']/(int)$sliderwidth;
			$dotsValue = 10/(int)$sliderwidth;
		}


		for($i=$sliderwidth; $i>148; $i = $i-28) {
		?>

		@media screen and (max-width: <?php echo $i;?>px) {

			.huge_it_slideshow_title_text_<?php echo $sliderID; ?> {

				font-size: <?php echo  $titleValue*$i;?>px !important;

			}
			.huge_it_slideshow_description_text_<?php echo $sliderID; ?> {

				font-size: <?php echo  $descValue*$i;?>px !important;

			}
			.huge_it_slideshow_dots_thumbnails_<?php echo $sliderID; ?> .huge_it_slideshow_dots_<?php echo $sliderID; ?> {

				width:<?php echo $dotsValue*$i; ?>px;
				height:<?php echo $dotsValue*$i; ?>px;
				border-radius:<?php echo $dotsValue*$i; ?>px;
				margin: <?php echo $dotsValue*$i; ?>px;

			}
		<?php

				$arrowfolder=plugins_url('slider-image/Front_images/arrows');
				$arrowValue = $i/$sliderwidth;
				switch ($paramssld['slider_navigation_type']) {
					case 1:
						?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:<?php echo -21*$arrowValue;?>px;
				height:<?php echo 43*$arrowValue;?>43px;
				width:<?php echo 29*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.simple.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:<?php echo -21*$arrowValue;?>px;
				height:<?php echo 43*$arrowValue;?>px;
				width:<?php echo 29*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.simple.png) right top no-repeat;
				background-size: 200%;

			}
		<?php
		break;
	case 2:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:<?php echo -25*$arrowValue;?>px;
				height:<?php echo 50*$arrowValue;?>px;
				width:<?php echo 50*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.circle.shadow.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:<?php echo -25*$arrowValue;?>px;
				height:<?php echo 50*$arrowValue;?>px;
				width:<?php echo 50*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.circle.shadow.png) right top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_left_<?php echo $sliderID; ?>:hover {
				background-position:left <?php echo -50*$arrowValue;?>px;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?>:hover {
				background-position:right <?php echo -50*$arrowValue;?>px;
			}
		<?php
		break;
	case 3:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:<?php echo -22*$arrowValue;?>px;
				height:<?php echo 44*$arrowValue;?>px;
				width:<?php echo 44*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.circle.simple.dark.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:<?php echo -22*$arrowValue;?>px;
				height:<?php echo 44*$arrowValue;?>px;
				width:<?php echo 44*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.circle.simple.dark.png) right top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_left_<?php echo $sliderID; ?>:hover {
				background-position:left <?php echo -44*$arrowValue;?>px;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?>:hover {
				background-position:right <?php echo -44*$arrowValue;?>px;
			}
		<?php
		break;
	case 4:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:<?php echo -33*$arrowValue;?>px;
				height:<?php echo 65*$arrowValue;?>px;
				width:<?php echo 59*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.cube.dark.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:<?php echo -33*$arrowValue;?>px;
				height:<?php echo 65*$arrowValue;?>px;
				width:<?php echo 59*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.cube.dark.png) right top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_left_<?php echo $sliderID; ?>:hover {
				background-position:left <?php echo 66*$arrowValue;?>px;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?>:hover {
				background-position:right <?php echo 66*$arrowValue;?>px;
			}
		<?php
		break;
	case 5:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:<?php echo -18*$arrowValue;?>px;
				height:<?php echo 37*$arrowValue;?>px;
				width:<?php echo 40*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.light.blue.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:<?php echo -18*$arrowValue;?>px;
				height:<?php echo 37*$arrowValue;?>px;
				width:<?php echo 40*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.light.blue.png) right top no-repeat;
				background-size: 200%;
			}

		<?php
		break;
	case 6:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:<?php echo -25*$arrowValue;?>px;
				height:<?php echo 50*$arrowValue;?>px;
				width:<?php echo 50*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.light.cube.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:<?php echo -25*$arrowValue;?>px;
				height:<?php echo 50*$arrowValue;?>px;
				width:<?php echo 50*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.light.cube.png) right top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_left_<?php echo $sliderID; ?>:hover {
				background-position:left <?php echo -50*$arrowValue;?>px;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?>:hover {
				background-position:right <?php echo -50*$arrowValue;?>px;
			}
		<?php
		break;
	case 7:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				right:0;
				margin-top:<?php echo -19*$arrowValue;?>px;
				height:<?php echo 38*$arrowValue;?>px;
				width:<?php echo 38*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.light.transparent.circle.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:<?php echo -19*$arrowValue;?>px;
				height:<?php echo 38*$arrowValue;?>px;
				width:<?php echo 38*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.light.transparent.circle.png) right top no-repeat;
				background-size: 200%;
			}
		<?php
		break;
	case 8:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:<?php echo -22*$arrowValue;?>px;
				height:<?php echo 45*$arrowValue;?>px;
				width:<?php echo 45*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:<?php echo -22*$arrowValue;?>px;
				height:<?php echo 45*$arrowValue;?>px;
				width:<?php echo 45*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.png) right top no-repeat;
				background-size: 200%;
			}
		<?php
		break;
	case 9:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:-<?php echo 22*$arrowValue;?>px;
				height:<?php echo 45*$arrowValue;?>px;
				width:<?php echo 45*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.circle.blue.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:-<?php echo 22*$arrowValue;?>px;
				height:<?php echo 45*$arrowValue;?>px;
				width:<?php echo 45*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.circle.blue.png) right top no-repeat;
				background-size: 200%;
			}
		<?php
		break;
	case 10:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:-<?php echo 24*$arrowValue;?>px;
				height:<?php echo 48*$arrowValue;?>px;
				width:<?php echo 48*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.circle.green.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:-<?php echo 24*$arrowValue;?>px;
				height:<?php echo 48*$arrowValue;?>px;
				width:<?php echo 48*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.circle.green.png) right top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_left_<?php echo $sliderID; ?>:hover {
				background-position:left -<?php echo 48*$arrowValue;?>px;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?>:hover {
				background-position:right -<?php echo 48*$arrowValue;?>px;
			}
		<?php
		break;
	case 11:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:-<?php echo 29*$arrowValue;?>px;
				height:<?php echo 58*$arrowValue;?>px;
				width:<?php echo 55*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.blue.retro.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:-<?php echo 29*$arrowValue;?>px;
				height:<?php echo 58*$arrowValue;?>px;
				width:<?php echo 55*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.blue.retro.png) right top no-repeat;
				background-size: 200%;
			}
		<?php
		break;
	case 12:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:<?php echo (-37*$arrowValue);?>px;
				height:<?php echo (74*$arrowValue);?>px;
				width:<?php echo (74*$arrowValue);?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.green.retro.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:-<?php echo 37*$arrowValue;?>px;
				height:<?php echo 74*$arrowValue;?>px;
				width:<?php echo 74*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.green.retro.png) right top no-repeat;
				background-size: 200%;
			}
		<?php
		break;
	case 13:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:-<?php echo 16*$arrowValue;?>px;
				height:<?php echo 33*$arrowValue;?>px;
				width:<?php echo 33*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.red.circle.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:-<?php echo 16*$arrowValue;?>px;
				height:<?php echo 33*$arrowValue;?>px;
				width:<?php echo 33*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.red.circle.png) right top no-repeat;
				background-size: 200%;
			}
		<?php
		break;
	case 14:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:-<?php echo 51*$arrowValue;?>px;
				height:<?php echo 102*$arrowValue;?>px;
				width:<?php echo 52*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.triangle.white.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:-<?php echo 51*$arrowValue;?>px;
				height:<?php echo 102*$arrowValue;?>px;
				width:<?php echo 52*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.triangle.white.png) right top no-repeat;
				background-size: 200%;
			}
		<?php
		break;
	case 15:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:0;
				margin-top:-<?php echo 19*$arrowValue;?>px;
				height:<?php echo 39*$arrowValue;?>px;
				width:<?php echo 70*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.ancient.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:0;
				margin-top:-<?php echo 19*$arrowValue;?>px;
				height:<?php echo 39*$arrowValue;?>px;
				width:<?php echo 70*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.ancient.png) right top no-repeat;
				background-size: 200%;
			}
		<?php
		break;
	case 16:
		?>
			#huge_it_slideshow_left_<?php echo $sliderID; ?> {
				left:-<?php echo 21*$arrowValue;?>px;
				margin-top:-<?php echo 20*$arrowValue;?>px;
				height:<?php echo 40*$arrowValue;?>px;
				width:<?php echo 37*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.black.out.png) left  top no-repeat;
				background-size: 200%;
			}

			#huge_it_slideshow_right_<?php echo $sliderID; ?> {
				right:-<?php echo 21*$arrowValue;?>px;
				margin-top:-<?php echo 20*$arrowValue;?>px;
				height:<?php echo 40*$arrowValue;?>px;
				width:<?php echo 37*$arrowValue;?>px;
				background:url(<?php echo $arrowfolder;?>/arrows.black.out.png) right top no-repeat;
				background-size: 200%;
			}
		<?php
		break;
}
?>
		}

		<?php } ?>

		/***</add>***/

	</style>


	<?php

	if ( isset( $GLOBALS['pause_time'] ) ) {
		$time_huge = $GLOBALS['pause_time'];
	} else {
		$time_huge = '';
	}
	if ( isset( $GLOBALS['changespeed'] ) ) {
		$speed_huge = $GLOBALS['changespeed'];
	} else {
		$speed_huge = '';
	}
	if ( ! isset( $paramssld['slider_thumb_count_slides'] ) ) {
		$paramssld['slider_thumb_count_slides'] = '';
	}
	$width_of_thumbs     = $width_huge / $paramssld['slider_thumb_count_slides'];
	$res_width_of_thumbs = intval( $width_of_thumbs );

	$translation_array = array(
		'slideCount'   => $paramssld['slider_thumb_count_slides'],
		'pauseTime'    => $time_huge,
		'width_thumbs' => $res_width_of_thumbs,
		'speed'        => $speed_huge,
	);
	wp_localize_script( 'bxSlider', 'hugeit_slider_obj', $translation_array );
}
/***</add>***/

function hugeit_slider_activate() {
	global $wpdb;
	$charset = $wpdb->get_charset_collate();
	$sql_huge_itslider_params = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "huge_itslider_params`(
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(50),
	`title` varchar(200) NOT NULL,
	`description` text NOT NULL,
	`value` varchar(200) NOT NULL,
 PRIMARY KEY (`id`)
) " . $charset . " AUTO_INCREMENT=89";

	$sql_huge_itslider_images = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "huge_itslider_images` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(100) DEFAULT NULL,
	`slider_id` varchar(200) ,
	`description` text,
	`image_url` text,
	`sl_url` varchar(128) DEFAULT NULL,
	`ordering` int(11) NOT NULL,
	`published` tinyint(4) unsigned DEFAULT NULL,
	`published_in_sl_width` tinyint(4) unsigned DEFAULT NULL,
PRIMARY KEY (`id`)
) " . $charset . " AUTO_INCREMENT=5";

	$sql_huge_itslider_sliders = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "huge_itslider_sliders` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(200) NOT NULL,
	`sl_height` int(11) unsigned DEFAULT NULL,
	`sl_width` int(11) unsigned DEFAULT NULL,
	`pause_on_hover` text,
	`slider_list_effects_s` text,
	`description` text,
	`param` text,
	`ordering` int(11) NOT NULL,
	`published` text,
PRIMARY KEY (`id`)
) " . $charset . " AUTO_INCREMENT=2";

	$table_name = $wpdb->prefix . "huge_itslider_params";
	$sql_1 = <<<query1
INSERT INTO `$table_name` (`name`, `title`,`description`, `value`) VALUES
( 'slider_crop_image', 'Slider crop image', 'Slider crop image', 'resize'),
( 'slider_title_color', 'Slider title color', 'Slider title color', '000000'),
( 'slider_title_font_size', 'Slider title font size', 'Slider title font size', '13'),
( 'slider_description_color', 'Slider description color', 'Slider description color', 'ffffff'),
( 'slider_description_font_size', 'Slider description font size', 'Slider description font size', '13'),
( 'slider_title_position', 'Slider title position', 'Slider title position', 'right-top'),
( 'slider_description_position', 'Slider description position', 'Slider description position', 'right-bottom'),
( 'slider_title_border_size', 'Slider Title border size', 'Slider Title border size', '0'),
( 'slider_title_border_color', 'Slider title border color', 'Slider title border color', 'ffffff'),
( 'slider_title_border_radius', 'Slider title border radius', 'Slider title border radius', '4'),
( 'slider_description_border_size', 'Slider description border size', 'Slider description border size', '0'),
( 'slider_description_border_color', 'Slider description border color', 'Slider description border color', 'ffffff'),
( 'slider_description_border_radius', 'Slider description border radius', 'Slider description border radius', '0'),
( 'slider_slideshow_border_size', 'Slider border size', 'Slider border size', '0'),
( 'slider_slideshow_border_color', 'Slider border color', 'Slider border color', 'ffffff'),
( 'slider_slideshow_border_radius', 'Slider border radius', 'Slider border radius', '0'),
( 'slider_navigation_type', 'Slider navigation type', 'Slider navigation type', '1'),
( 'slider_navigation_position', 'Slider navigation position', 'Slider navigation position', 'bottom'),
( 'slider_title_background_color', 'Slider title background color', 'Slider title background color', 'ffffff'),
( 'slider_description_background_color', 'Slider description background color', 'Slider description background color', '000000'),
( 'slider_title_transparent', 'Slider title has background', 'Slider title has background', 'on'),
( 'slider_description_transparent', 'Slider description has background', 'Slider description has background', 'on'),
( 'slider_slider_background_color', 'Slider slider background color', 'Slider slider background color', 'ffffff'),
( 'slider_dots_position', 'slider dots position', 'slider dots position', 'top'),
( 'slider_active_dot_color', 'slider active dot color', '', 'ffffff'),
( 'slider_dots_color', 'slider dots color', '', '000000');


query1;

	$table_name = $wpdb->prefix . "huge_itslider_images";
	$sql_2 = "
INSERT INTO 
`" . $table_name . "` (`id`, `slider_id`, `name`, `description`, `image_url`, `sl_url`, `ordering`, `published`) VALUES
(1, '1', '',  '', '" . plugins_url("Front_images/slides/slide1.jpg", __FILE__) . "', 'http://huge-it.com',  1, 1),
(2, '1', 'Simple Usage',  '', '" . plugins_url("Front_images/slides/slide2.jpg", __FILE__) . "', 'http://huge-it.com',  2, 1),
(3, '1', 'Huge-IT Slider',  'The slider allows having unlimited amount of images with their titles and descriptions. The slider uses autogenerated shortcodes making it easier for the users to add it to the custom location.', '" . plugins_url("Front_images/slides/slide3.jpg", __FILE__) . "', 'http://huge-it.com',  3, 1)";


	$table_name = $wpdb->prefix . "huge_itslider_sliders";


	$sql_3 = "
INSERT INTO `$table_name` (`id`, `name`, `sl_height`, `sl_width`, `pause_on_hover`, `slider_list_effects_s`, `description`, `param`, `ordering`, `published`) VALUES
(1, 'My First Slider', '375', '600', 'on', 'random', '4000', '1000', '1', '300')";




	$wpdb->query($sql_huge_itslider_params);
	$wpdb->query($sql_huge_itslider_images);
	$wpdb->query($sql_huge_itslider_sliders);

	if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "huge_itslider_params")) {
		$wpdb->query($sql_1);
	}
	if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "huge_itslider_images")) {
		$wpdb->query($sql_2);
	}
	if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "huge_itslider_sliders")) {
		$wpdb->query($sql_3);
	}

	$product = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "huge_itslider_sliders", ARRAY_A);
	$isUpdate = 0;
	foreach ($product as $prod) {
		if ($prod['Field'] == 'published' && ($prod['Type'] == 'tinyint(4) unsigned')) {
			$isUpdate = 1;
			break;
		}
	}
	if ($isUpdate) {
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."huge_itslider_sliders MODIFY `published` text");
		$wpdb->query("UPDATE ".$wpdb->prefix."huge_itslider_sliders SET published = '300' WHERE id = 1 ");
	}

	$product2 = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "huge_itslider_images", ARRAY_A);
	foreach ($product2 as $prod2) {

		if($product2[6]['Field'] == 'sl_type') {
			echo '';
		} else {
			$query="SELECT * FROM ".$wpdb->prefix."huge_itslider_images order by id ASC";
			$rowim=$wpdb->get_results($query);
			foreach ($rowim as $key=>$rowimages){
				$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itslider_images SET  ordering = '%s'  WHERE id = %d ", $rowimages->id,$rowimages->id));
			}
		}
	}

	if($product2[6]['Field'] == 'sl_type') {
		echo '';
	} else {
		$wpdb->query("ALTER TABLE  ".$wpdb->prefix."huge_itslider_images ADD  `sl_type` TEXT NOT NULL AFTER  `sl_url`");
		$wpdb->query("UPDATE ".$wpdb->prefix."huge_itslider_images SET sl_type = 'image' ");
		$wpdb->query("ALTER TABLE  ".$wpdb->prefix."huge_itslider_images ADD  `link_target` TEXT NOT NULL AFTER  `sl_type`");
		$wpdb->query("UPDATE ".$wpdb->prefix."huge_itslider_images SET link_target = 'on' ");

		$table_name = $wpdb->prefix . "huge_itslider_params";
		$sql_update2 = <<<query1
INSERT INTO `$table_name` (`name`, `title`,`description`, `value`) VALUES
( 'slider_description_width', 'Slider description width', 'Slider description width', '70'),
( 'slider_description_height', 'Slider description height', 'Slider description height', '50'),
( 'slider_description_background_transparency', 'slider description background transparency', 'slider description background transparency', '70'),
( 'slider_description_text_align', 'description text-align', 'description text-align', 'justify'),
( 'slider_title_width', 'slider title width', 'slider title width', '30'),
( 'slider_title_height', 'slider title height', 'slider title height', '50'),
( 'slider_title_background_transparency', 'slider title background transparency', 'slider title background transparency', '70'),
( 'slider_title_text_align', 'title text-align', 'title text-align', 'right');

query1;
		$wpdb->query($sql_update2);
	}
	$product3 = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "huge_itslider_sliders", ARRAY_A);
	if($product3[8]['Field'] == 'sl_position'){
		echo '';
	} else {
		$wpdb->query("ALTER TABLE  ".$wpdb->prefix."huge_itslider_sliders ADD  `sl_position` TEXT NOT NULL AFTER  `param`");
		$wpdb->query("UPDATE ".$wpdb->prefix."huge_itslider_sliders SET `sl_position` = 'center' ");
		$table_name = $wpdb->prefix . "huge_itslider_params";
		$sql_update3 = <<<query1
INSERT INTO `$table_name` (`name`, `title`,`description`, `value`) VALUES
( 'slider_title_has_margin', 'title has margin', 'title has margin', 'on'),
( 'slider_description_has_margin', 'description has margin', 'description has margin', 'on'),
( 'slider_show_arrows', 'Slider show left right arrows', 'Slider show left right arrows', 'on');

query1;
		$wpdb->query($sql_update3);
	}
	$productSliders = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "huge_itslider_sliders", ARRAY_A);//get table fields
	$isUpdate1 = 0;
	foreach ($productSliders as $PSlider) {
		if ($PSlider['Field'] == 'sl_loading_icon') {
			$isUpdate1 = 1;
			break;
		}
	}
	if ($isUpdate1 == 0) {
		$wpdb->query("ALTER TABLE "  .$wpdb->prefix . "huge_itslider_sliders ADD `sl_loading_icon` text NOT NULL AFTER `published`");
		$wpdb->query("UPDATE " . $wpdb->prefix ."huge_itslider_sliders SET `sl_loading_icon` = 'off' ");
	}

	$product4 = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "huge_itslider_images", ARRAY_A);
	if($product4[8]['Field'] != 'sl_stitle') {
		$wpdb->query("ALTER TABLE  ".$wpdb->prefix."huge_itslider_images ADD  `sl_stitle` TEXT NOT NULL AFTER  `link_target`");
		$wpdb->query("ALTER TABLE  ".$wpdb->prefix."huge_itslider_images ADD  `sl_sdesc` TEXT NOT NULL AFTER  `sl_stitle`");
		$wpdb->query("ALTER TABLE  ".$wpdb->prefix."huge_itslider_images ADD  `sl_postlink` TEXT NOT NULL AFTER  `sl_sdesc`");
	}

	$table_name = $wpdb->prefix . "huge_itslider_params";
	$sql_update4 = <<<query2
INSERT INTO `$table_name` (`name`, `title`,`description`, `value`) VALUES
('loading_icon_type', 'Slider loading icon type', 'Slider loading icon type', '1');
query2;
	$query3="SELECT name FROM ".$table_name;
	$update_p3=$wpdb->get_results($query3);
	if(end($update_p3)->name=='slider_show_arrows'){
		$wpdb->query($sql_update4);
	}

	///////////////
	$table_name = $wpdb->prefix . "huge_itslider_params";
	$sql_update_g6 = <<<query6
INSERT INTO `$table_name` (`name`, `title`,`description`, `value`) VALUES
('slider_thumb_count_slides', 'Slide thumbs count', 'Slide thumbs count', '3'),
('slider_dots_position_new', 'Slide Dots Position', 'Slide Dots Position', 'dotstop'),
('slider_thumb_back_color','Thumbnail Background Color','Thumbnail Background Color','FFFFFF'),
('slider_thumb_passive_color','Passive Thumbnail Color','Passive Thumbnail Color','FFFFFF'),
('slider_thumb_passive_color_trans','Passive Thumbnail Color Transparency','Passive Thumbnail Color Transparency','50'),
('slider_thumb_height', 'Slider Thumb Height', 'Slider Thumb Height', '100');                
query6;


	$query6="SELECT name FROM ".$wpdb->prefix."huge_itslider_params";
	$update_p6=$wpdb->get_results($query6);
	if(end($update_p6)->name=='loading_icon_type'){
		$wpdb->query($sql_update_g6);
	}

///////////////////////////////////////////////////////////////////////
	$imagesAllFieldsInArray3 = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "huge_itslider_sliders", ARRAY_A);
	$fornewUpdate2 = 0;
	foreach ( $imagesAllFieldsInArray3 as $portfoliosField3 ) {
		if ( $portfoliosField3['Field'] == 'show_thumb' ) {
			$fornewUpdate2 = 1;
		}
	}
	if($fornewUpdate2 != 1){
		$wpdb->query("ALTER TABLE `".$wpdb->prefix."huge_itslider_sliders` ADD `show_thumb` VARCHAR(255) NOT NULL DEFAULT 'dotstop' AFTER `sl_loading_icon`");

	}
	$imagesAllFieldsInArray4 = $wpdb->get_results( "DESCRIBE " . $wpdb->prefix . "huge_itslider_sliders", ARRAY_A );
	$fornewUpdate3           = 0;
	foreach ( $imagesAllFieldsInArray4 as $portfoliosField4 ) {
		if ( $portfoliosField4['Field'] == 'video_autoplay' ) {
			$fornewUpdate3 = 1;
		}
	}
	if($fornewUpdate3 != 1){
		$wpdb->query("ALTER TABLE `".$wpdb->prefix."huge_itslider_sliders` ADD `video_autoplay` VARCHAR(255) NOT NULL DEFAULT 'off' AFTER `show_thumb`");

	}
	$imagesAllFieldsInArray5 = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "huge_itslider_sliders", ARRAY_A);
	$fornewUpdate4 = 0;
	foreach ($imagesAllFieldsInArray5 as $portfoliosField5) {
		if ($portfoliosField5['Field'] == 'random_images') {
			$fornewUpdate4=1;
		}
	}
	if($fornewUpdate4 != 1){
		$wpdb->query("ALTER TABLE `".$wpdb->prefix."huge_itslider_sliders` ADD `random_images` VARCHAR(255) NOT NULL DEFAULT 'off' AFTER `video_autoplay`");
	}
	/****<change image table url type>****/

	$table_name =  $wpdb->prefix."huge_itslider_images";

	$sql_huge_itslider_images_change_column_type = "ALTER TABLE `$table_name` MODIFY COLUMN `sl_url` text ";

	$wpdb->query($sql_huge_itslider_images_change_column_type);

	/****</change image table url type>****/
	$plugin_version = '3.1.94';
	$plugin_new_version = '3.2';
	if(get_option('slider_image_version') != $plugin_new_version){
		update_option('slider_image_version',$plugin_version);
		update_option('slider_image_imege_hover_preview','on');
		update_option('slider_image_version',$plugin_new_version);
	}
}
register_activation_hook(plugins_url(plugin_basename( __FILE__ ),__FILE__), 'hugeit_slider_activate' );
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$plugin_info = get_plugin_data( ABSPATH . 'wp-content/plugins/slider-image/slider.php' );
if($plugin_info['Version'] > '2.9.2'){
	hugeit_slider_activate();
}

add_action('wp_loaded','hugeit_slider_duplicate');

/**
 * Duplicate Slider
 */
function hugeit_slider_duplicate() {
	if (isset($_GET["id"])) {
		$id = absint($_GET["id"]);
	} else {
		return false;
	}


	if (isset($_GET['page']) && $_GET['page'] === 'sliders_huge_it_slider') {
		if (isset($_GET["task"]) && $_GET["task"] === 'duplicate_slider_image') {
			if ( ! isset( $_REQUEST['hugeit_slider_duplicate_slide_nonce'] ) || ! wp_verify_nonce( $_REQUEST['hugeit_slider_duplicate_slide_nonce'], 'duplicate_slider' . $id ) ) {
				wp_die( 'Security check fail' );
			}
			global $wpdb;
			$table_name = $wpdb->prefix . "huge_itslider_sliders";
			$query = $wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id=%d", $id);
			$slider_img = $wpdb->get_results($query);
			$wpdb->insert(
				$table_name,
				array(
					'name' => $slider_img[0]->name . ' Copy',
					'sl_height' => $slider_img[0]->sl_height,
					'sl_width' => $slider_img[0]->sl_width,
					'pause_on_hover' => $slider_img[0]->pause_on_hover,
					'slider_list_effects_s' => $slider_img[0]->slider_list_effects_s,
					'description' => $slider_img[0]->description,
					'param' => $slider_img[0]->param,
					'sl_position' => $slider_img[0]->sl_position,
					'ordering' => $slider_img[0]->ordering,
					'published' => $slider_img[0]->published,
					'sl_loading_icon' => $slider_img[0]->sl_loading_icon,
					'show_thumb' => $slider_img[0]->show_thumb,
					'video_autoplay' => $slider_img[0]->video_autoplay,
					'random_images' => $slider_img[0]->random_images,
				)
			);
			$last_key = $wpdb->insert_id;
			$table_name = $wpdb->prefix . "huge_itslider_images";
			$query = $wpdb->prepare("SELECT * FROM " . $table_name . " WHERE slider_id=%d", $id);
			$sliders = $wpdb->get_results($query);
			$sliders_list = "";
			foreach ($sliders as $key => $slider) {
				$new_slider = "('";
				$new_slider .= $slider->name . "','" . $last_key . "','" . $slider->description . "','" . $slider->image_url . "','" .
					$slider->sl_url . "','" . $slider->sl_type . "','" . $slider->link_target . "','" . $slider->sl_stitle . "','" .
					$slider->sl_sdesc . "','" . $slider->sl_postlink . "','" . $slider->ordering . "','" .
					$slider->published . "','" . $slider->published_in_sl_width . "')";
				$sliders_list .= $new_slider . ",";
			}
			$sliders_list = substr($sliders_list, 0, strlen($sliders_list) - 1);
			$query = "INSERT into " . $table_name . " (`name`,`slider_id`,`description`,`image_url`,`sl_url`,`sl_type`,`link_target`,`sl_stitle`,`sl_sdesc`,`sl_postlink`,`ordering`,`published`,`published_in_sl_width`)
				VALUES " . $sliders_list;
			$wpdb->query($query);
			wp_redirect('admin.php?page=sliders_huge_it_slider');
		}
	}

	return false;
}
