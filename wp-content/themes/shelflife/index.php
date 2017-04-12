<?php
/**
 * Index Template
 *
 * Here we setup all logic and XHTML that is required for the index template, used as both the homepage
 * and as a fallback template, if a more appropriate template file doesn't exist for a specific context.
 *
 * @package WooFramework
 * @subpackage Template
 */
	get_header();
	global $woo_options;
	
/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */
	
	$settings = array(
					'thumb_w' => 100, 
					'thumb_h' => 100, 
					'thumb_align' => 'alignleft', 
					'post_content' => 'excerpt', 
					'posts_limit' => get_option( 'posts_per_page' ), 
					'homepage_show_pagination' => 'true', 
					'moreposts_page' => '', 
					'blog_enable' => 'true'
					);
					
	$settings = woo_get_dynamic_values( $settings );
?>

    <div id="content" class="col-full">
<?php
	$paged = get_query_var( 'paged' );
	if ( $paged <= 1 ) {
?>	
    	<section id="homepage-top" class="fix">
    	
    		<?php
    			// Load featured products section.
    			get_template_part( 'includes/featured', 'products' );
    		?>
    		
    		<div id="pop-promo">
    		
    			<?php
	    			// Load popular products section.
	    			get_template_part( 'includes/popular', 'products' );
	    			
	    			// Load promotions section.
	    			get_template_part( 'includes/promotions' );
    			?>
    			
    		</div><!-- /#pop-promo -->
    			
    	</section>
    			
    	<?php
    		// Load mini-features section.
    		get_template_part( 'includes/mini', 'features' );
    		
    		// Load "more products" section.
    		get_template_part( 'includes/more', 'products' );
    		
    		// Load search bar section.
    		get_template_part( 'includes/search', 'bar' );
    		
    		// Make sure the query is restored to it's original state before we begin the general content display.
    		wp_reset_query();
    
    } // End $paged <= 1 IF Statement
    
    if ( $settings['blog_enable'] == 'true' ) {
    
    		if ( $settings['posts_limit'] > 0 ) {
    		
    		// Make sure we only display X number of blog posts.
    		$args = array( 'posts_per_page' => $settings['posts_limit'], 'paged' => get_query_var( 'paged' ) );
    		query_posts( $args );
    	?>
    	
		<section id="main" class="col-left">      
        <?php          
			if ( have_posts() ) {
        		$count = 0;
        		while ( have_posts() ) { the_post(); $count++;
        		
        		$class = '';
        		if ( $count == $settings['posts_limit'] ) {
        			$class = 'last';
        		}
        ?>                                                            
            <!-- Post Starts -->
            <article <?php post_class( $class ); ?>>

                <?php if ( $settings['post_content'] != 'content' ) { woo_image( 'width=' . $settings['thumb_w'] . '&height=' . $settings['thumb_h'] . '&class=thumbnail ' . $settings['thumb_align'] ); } ?>
                
                <header>
                	<h1><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
                </header>
                
                <?php woo_post_meta(); ?>
                
                <section class="entry fix">
					<?php
						global $more; $more = 0;
						if ( $settings['post_content'] == 'content' ) {
							the_content( __( 'Read More...', 'woothemes' ) );
						} else {
							the_excerpt();
						}
					?>
                </section>
    			
                <footer class="post-more">      
					<span class="comments"><?php comments_popup_link( __( 'Leave a comment', 'woothemes' ), __( '1 Comment', 'woothemes' ), __( '% Comments', 'woothemes' ) ); ?></span>
                	<?php if ( $settings['post_content'] == 'excerpt' ) { ?>
					<span class="post-more-sep">&bull;</span>
                    <span class="read-more"><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Continue Reading &rarr;', 'woothemes' ); ?>"><?php _e( 'Continue Reading &rarr;', 'woothemes' ); ?></a></span>
                    <?php } ?>
                </footer>   
    
            </article><!-- /.post -->
                                                
        <?php
        		} // End WHILE Loop
        	
        	} else {
        ?>
            <article <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
        <?php } // End IF Statement ?>
        <?php
        	if ( $settings['homepage_show_pagination'] == 'true' ) {
        		woo_pagenav();
        	} else {
        		if ( $settings['moreposts_page'] != '' ) {
        ?>
        	<div class="more-posts-link">
				<a class="button" href="<?php echo esc_url( get_permalink( $settings['moreposts_page'] ) ); ?>" title="<?php esc_attr_e( 'More Posts', 'woothemes' ); ?>"><?php _e( 'More Posts', 'woothemes' ); ?></a>
			</div>
        <?php
        		}
        	}
        	
        	} // End posts_limit > 0 IF Statement
        } // Enable Blog Section IF Statement
        ?>
                
		</section><!-- /#main -->
		
		<?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>