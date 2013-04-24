<?php  
/* 
    Plugin Name: wp-Architect Slideshow Plugin 
    Description: Simple Nivo Slideshow for WP-Architect Theme
    Author: Matthew Ell 
    Version: 1.0 
*/  

// Creates Admin Menu "Slides" Custom Post type

function np_init() {  
    
    $args = array(  
        'public' => true,  
        'label' => 'Nivo Images',  
        'supports' => array(  
            'title',  
            'thumbnail'  
        ) 
    ); 
    register_post_type('np_images', $args);  
}  

add_action('init', 'np_init');


// // Create Image Size for Slides
add_image_size('np_function', 600, 280, true); 

// // Thumbnail Support
add_theme_support( 'post-thumbnails' ); 

// // // Create Slideshow
function np_function($type='np_function') {  
    $args = array(  
        'post_type' => 'np_images',  
        'posts_per_page' => 5  
    );  
    $result = '<div class="slider-wrapper theme-default">';  
    $result .= '<div id="slider" class="nivoSlider">';  
  
    //the loop  
    $loop = new WP_Query($args);  
    while ($loop->have_posts()) {  
        $loop->the_post();  
        $the_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $type);  
        $result .='<img title="'.get_the_title().'" src="' . $the_url[0] . '" data-thumb="' . $the_url[0] . '" alt=""/>';  
    }  
    $result .= '</div>';  
    $result .='<div id = "htmlcaption" class = "nivo-html-caption">';  
    $result .='<strong>This</strong> is an example of a <em>HTML</em> caption with <a href = "#">a link</a>.';  
    $result .='</div>';  
    $result .='</div>';  
    return $result;  
}  

add_shortcode('np-shortcode', 'np_function');  

?>