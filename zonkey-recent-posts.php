<?php
/*
  Plugin Name: Zonkey Recent Posts
  Plugin URI: http://www.zonkey.co.uk
  Description: Creates shortcode for recent posts of any taxonomy, tag or post type, 
  Author: Matthew Poppell
  Author URI: http://www.zonkey.co.uk
  Text Domain: zonkey-recent-posts
  Version: 0.3.2

  Copyright 2016 - Matthew Poppell

*/


function zonkey_recent_posts_shortcode( $atts ) {
// Extract Attributes that are passed through the shortcode

    extract( shortcode_atts(
            array(
                'post_type' => 'post',
                'taxonomy' => 'category',
                'terms' =>  false,
                'title' => false,
                'style' => '1',
                'class' => 'standard-post-display',
                'amount' => '1',
                'columns' => '1',
                'featured_image'=> true,
                'show_categories' => false,
                'show_headings' => true,
                'show_full_post' => false,
                'show_date' => false,
                'excerpt_length'=> 180,
                'orderby'=> 'date',
                'order'=> 'DESC'
            ), $atts )
    ); //takes the arguments posted in the shortcode, assigns a default value if necessary.

// Code
    $found_none = '<p>No recent posts yet</p>'; // what to display if there are no recent posts
    $counter=0; // ensure the counter is set to 0
    $max_posts_display= $amount; //set the number of related posts to show

    $zrp_args = array(
        'post_type'         => $post_type,
        'posts_per_page'    => $max_posts_display,
        'order'             => $order,
        'orderby'           => $orderby, // latest first
       // 'tax_query'         => $tax_query
    );

    if ($terms) {
        $zrp_args[$taxonomy]= $terms;
    }

    $backup = $post;
    $items = get_posts($zrp_args);
    $posts_display = '<div class="zonkey-recent-posts ' .  $class . '">';


  //  var_dump($zrp_args, $items);
    if($title) {
        $posts_display .= '<h3><a href="'. get_home_url().'/'.$taxonomy.'/'. $terms . '/" title="'. $title.'">' . $title . '</a></h3>';
    }

    if( !empty($items)) {
        foreach($items as $post):
            setup_postdata($post);
                $post_ID = $post->ID;
                $posts_display .= '<div class="zonkey-recent-post col-' . $columns . '">';
                $posts_display .= '<div class="zrp-inner">';
                if ((has_post_thumbnail($post_ID)) && ($featured_image)) {
                    $posts_display .='<a href="' . get_permalink($post_ID) . '"><span class="post-featured-img">'. get_the_post_thumbnail($post_ID) .'</span></a>';
                }

                if($show_headings) {
                    $posts_display .= '<h3><a href="' . get_permalink($post_ID) . '" rel="bookmark" title="' . get_the_title($post_ID) . '">' . get_the_title($post_ID) . '</a></h3>';
                }
                //$posts_display .= $show_headings;
                if($show_categories){
                    $posts_display .= '<h4>' . get_the_term_list($post_ID , $taxonomy, '', ', ') . '</h4>';
                }
                if ((!$show_full_post)) {
                    $posts_display .= '<p class="zrp-excerpt">' . substr(strip_tags(apply_filters('the_content', get_the_content())), 0, $excerpt_length) . '...</p>';
                } else {
                    $posts_display .= '<div class="zrp-content">' . apply_filters('the_content', get_the_content()) . '</div>';
                }
                if($show_date){$posts_display .= '<span class="zrp-date">'. get_the_date('M j, Y', $post_ID) . '</span>';}
                $posts_display .= '</div></div>';
                $found_none = false;
        endforeach;
        wp_reset_postdata();
    }
    if ($found_none){
        $posts_display .= $found_none;
    }

    $posts_display .= '</div><div class="clearfix"></div>';

    $post = $backup;  // copy it back

    return $posts_display;
}
add_shortcode( 'zonkey_recent_posts', 'zonkey_recent_posts_shortcode' );


function zonkey_recent_posts_register_scripts() {
    wp_register_script("zonkey_recent_posts-js", plugin_dir_url(__FILE__) . "js/zrp.js", array('jquery'), '1.0.0', true);
    wp_enqueue_script(" zonkey_recent_posts-js");

    // wp_register_style("zonkey_recent_posts-css", plugin_dir_url(__FILE__) . "css/zrp.css");
    // wp_enqueue_style("zonkey_recent_posts-css");
}

add_action('wp_enqueue_scripts', 'zonkey_recent_posts_register_scripts');

?>