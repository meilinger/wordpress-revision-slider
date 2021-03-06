<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

wp_enqueue_script( "jquery-ui-slider");
wp_enqueue_script( "jquery-ui-datepicker");
wp_enqueue_script( "difflib", get_template_directory_uri() . '/js/diff_match_patch.js');
wp_enqueue_script( "rangeJS", get_template_directory_uri() . '/js/revisionslider.js');
wp_enqueue_script( "scrollTo", 'https://cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.0/jquery.scrollTo.min.js');

add_action('wp_enqueue_scripts', 'load_css_files');

function load_css_files() {
   wp_register_style( 'rangeCSS', get_template_directory_uri() . '/css/revisionslider.css');
    wp_register_style( 'Twenty Fourteen', get_stylesheet_uri(), array( 'rangeCSS' ));
    wp_enqueue_style( "rangeCSS");
    wp_enqueue_style( "diffCSS", get_template_directory_uri() . '/css/diffview.css');
    wp_enqueue_style("jquery-ui-css", "https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.min.css");
}

wp_localize_script( 'rangeJS', 'ajax_object',
                   array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 
                      'rev_url' => 'index.php', 
                      'postID' =>  $wp_query->post->ID,
                      'revisions' => wp_get_post_revisions( $wp_query->post->ID ),
                     'post_type' =>  get_post_type(  $wp_query->post->ID )
                    ) 
);


get_header(); 



          
                
                
?>

<div id="main-content" class="main-content">



       <?php  the_title( '<h1 class="entry-title">', '</h1>' );  ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content pathfinder-3col" role="main">	
            <div id="left-col" class="pathfinder-column">
                <div id="background-explainer" >
                    <h2 class="page-title background-title">Background</h2>
                    <?php echo get_field('background_explainer', get_the_ID() ); ?>    
                </div  >


                  <div id="latest-posts" >
                    
                    <?php 
                        $categories =  get_the_category(get_the_ID() );
                      
                        echo '<h2 class="page-title background-title">latest posts in <i>' . $categories[0]->name . '</i></h2>';
                        $catPosts = new WP_Query('category_name='. $categories[0]->name .'&showposts=5'); 
                             echo "<ul>";
                        while ($catPosts->have_posts()) : $catPosts->the_post()  ;
                           // $catPosts->the_post();
                           
                                 echo '<li><a href="'. get_the_permalink() .'" rel="bookmark">'. get_the_title() . '</a></li>'; 
                        endwhile; 
                        echo "</ul>";
                        wp_reset_postdata();
                    ?>
                </div>
                
                <?php
                    if(get_field('feed_url')){
                        echo '<div id="paperli-feed">';
                  
                echo '<iframe width="500" height="300" scrolling="yes" frameborder="yes" src="' . get_field('feed_url', get_the_ID() ) . '"></iframe>';
                        echo "</div>";
                    }
                ?>
            </div>
            <div id="center-col">
            <?php

                if(get_field('story_post_id')){
                //echo "i have a story post id, it's " . get_field('story_post_id');
             //   query_posts('p='. get_field('story_post_id') );
                while (have_posts()): the_post();
                
					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );

					// Previous/next post navigation.
					//twentyfourteen_post_nav();

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>
            </div>
            <div id="revChanger" class="pathfinder-column">
                 <div class="rev-select-box">
                <form> 
                    <span class='ui-icon ui-icon-triangle-1-w' style='display:inline-block;'></span>
                    <select id='revSelect'></select>
                    <span class='ui-icon ui-icon-triangle-1-e' style='display:inline-block;'></span>
                </form>
                     </div>
                <div id='slider-vertical'></div>
            </div>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php 
            } 
get_sidebar( 'content' );
get_sidebar();
get_footer();
