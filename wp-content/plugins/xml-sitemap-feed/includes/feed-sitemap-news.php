<?php
/**
 * Google News Sitemap Feed Template
 *
 * @package XML Sitemap Feed plugin for WordPress
 */

if ( ! defined( 'WPINC' ) ) die;

global $xmlsf;

$options = $xmlsf->get_option('news_tags');

if ( !empty($options['image']) ) {
	$image_xmlns = '	xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"'.PHP_EOL;
	$image_schema = '
		http://www.google.com/schemas/sitemap-image/1.1
		http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd';
} else {
	$image_xmlns = '';
	$image_schema = '';
}

// start output
echo $xmlsf->headers('news');
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
<?php echo $image_xmlns; ?>
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
		http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd
		http://www.google.com/schemas/sitemap-news/0.9
		http://www.google.com/schemas/sitemap-news/0.9/sitemap-news.xsd<?php echo $image_schema; ?>">
<?php

// set empty news sitemap flag
$have_posts = false;

// loop away!
if ( have_posts() ) :
    while ( have_posts() ) :
	the_post();

	// check if we are not dealing with an external URL :: Thanks to Francois Deschenes :)
	// or if post meta says "exclude me please"
	$exclude = get_post_meta( $post->ID, '_xmlsf_news_exclude', true );
	if ( !empty($exclude) || !$xmlsf->is_allowed_domain(get_permalink()) )
		continue;

	$have_posts = true;
	?>
	<url>
		<loc><?php echo esc_url( get_permalink() ); ?></loc>
		<news:news>
			<news:publication>
				<news:name><?php
					if(!empty($options['name']))
						echo apply_filters( 'the_title_xmlsitemap', $options['name'] );
					elseif(defined('XMLSF_GOOGLE_NEWS_NAME'))
						echo apply_filters( 'the_title_xmlsitemap', XMLSF_GOOGLE_NEWS_NAME );
					else
						echo apply_filters( 'the_title_xmlsitemap', get_bloginfo('name') ); ?></news:name>
				<news:language><?php echo $xmlsf->get_language($post->ID); ?></news:language>
			</news:publication>
			<news:publication_date><?php
				echo mysql2date('Y-m-d\TH:i:s+00:00', $post->post_date_gmt, false); ?></news:publication_date>
			<news:title><?php echo apply_filters( 'the_title_xmlsitemap', get_the_title() ); ?></news:title>
<?php
	// access tag
	$access = get_post_meta( $post->ID, '_xmlsf_news_access', true );

	if (empty($access)) // if not set per meta, let's get global settings
	  if (!empty($options['access']))
			if ( post_password_required() )
				if (!empty($options['access']['password']))
					$access = $options['access']['password'];
			else
				if (!empty($options['access']['default']))
					$access = $options['access']['default'];

	if (!empty($access) && $access != 'Public' ) {
	?>
			<news:access><?php echo $access; ?></news:access>
<?php
	}

	// genres tag
	$genres = '';
	$terms = get_the_terms($post->ID,'gn-genre');
	if ( is_array($terms) ) {
		$sep = '';
		foreach($terms as $obj) {
			if (!empty($obj->name)) {
				$genres .= $sep . $obj->name;
				$sep = ', ';
			}
		}
	}

	$genres = trim(apply_filters('the_title_xmlsitemap', $genres));

	if ( empty($genres) && !empty($options['genres']) && !empty($options['genres']['default']) ) {
		$genres = implode( ', ', (array)$options['genres']['default'] );
	}

	if ( !empty($genres) ) {
	?>
			<news:genres><?php echo $genres; ?></news:genres>
<?php
	}

	// keywords tag
	$keywords = '';
	if( !empty($options['keywords']) ) {
		if ( !empty($options['keywords']['from']) ) {
			$terms = get_the_terms( $post->ID, $options['keywords']['from'] );
			if ( is_array($terms) ) {
				$sep = '';
				foreach($terms as $obj) {
					if (!empty($obj->name)) {
						$keywords .= $sep . $obj->name;
						$sep = ', ';
					}
				}
			}
		}

		$keywords = trim(apply_filters('the_title_xmlsitemap', $keywords));

		if ( empty($keywords) && !empty($options['keywords']['default']) ) {
			$keywords = trim(apply_filters('the_title_xmlsitemap', $options['keywords']['default']));
		}

	}

	if ( !empty($keywords) ) {
	?>
			<news:keywords><?php echo $keywords; ?></news:keywords>
<?php
	}

	/* xmlsf_news_tags_after action hook */
	do_action( 'xmlsf_news_tags_after' );
	?>
		</news:news>
<?php
	if ( !empty($options['image']) && $xmlsf->get_images('news') ) :
		foreach ( $xmlsf->get_images() as $image ) {
			if ( empty($image['loc']) )
				continue;
	?>
		<image:image>
			<image:loc><?php echo utf8_uri_encode( $image['loc'] ); ?></image:loc>
<?php
		if ( !empty($image['title']) ) {
		?>
			<image:title><![CDATA[<?php echo str_replace(']]>', ']]&gt;', $image['title']); ?>]]></image:title>
<?php
		}
		if ( !empty($image['caption']) ) {
		?>
			<image:caption><![CDATA[<?php echo str_replace(']]>', ']]&gt;', $image['caption']); ?>]]></image:caption>
<?php
		}
		?>
		</image:image>
<?php
		}
	endif;
	?>
	</url>
<?php
    endwhile;
endif;

if ( !$have_posts ) :
	// No posts done? Then do at least the homepage to prevent error message in GWT.
	?>
	<url>
		<loc><?php echo esc_url( home_url() ); ?></loc>
	</url>
<?php
endif;

?></urlset>
<?php $xmlsf->_e_usage();
