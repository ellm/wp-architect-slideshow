<?php  
/* 
    Plugin Name: wp-Architect Slideshow Plugin 
    Description: Simple Nivo Slideshow for WP-Architect Theme
    Author: Matthew Ell 
    Version: 1.0 
*/  

// Creates Admin Menu "Slides" Custom Post type

function wp_arch_ss_init() {  
    
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

add_action('init', 'wp_arch_ss_init');

// Enqueue Styles and Scripts 
// http://codex.wordpress.org/Determining_Plugin_and_Content_Directories
function wp_arch_ss_enqueue() {
    
    // enqueue css
    wp_enqueue_style('wp_arch_slideshow_styles', plugins_url('nivo-slider.css', __FILE__), array(), '01', 'all');
    wp_enqueue_style('wp_arch_slideshow_themes_styles', plugins_url('/themes/default/default.css', __FILE__), array(), '01', 'all');

    // If jQuery is not loaded, load jQuery
    wp_enqueue_script('wp_arch_jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js", array(), '1.8', true);

    // enqueue script | @Dependents: jQuery
    wp_enqueue_script('wp_arch_slideshow_scripts', plugins_url('jquery.nivo.slider.pack.js', __FILE__), array('wp_arch_jquery'), "1", true);

    // enqueue script | @Dependents: jQuery & wp_arch_lightbox_scripts
    wp_enqueue_script('wp_arch_slideshow_scripts_init', plugins_url('nivo.js', __FILE__), array('wp_arch_slideshow_scripts'), "1", true);
}


add_action('wp_enqueue_scripts', 'wp_arch_ss_enqueue');


// // Create Image Size for Slides
add_image_size('np_function', 1024, 399, true); 

// // Thumbnail Support
add_theme_support( 'post-thumbnails' ); 

// // // Create Slideshow
function np_function($type='np_function') {  
    
    $args = array(  
        'post_type' => 'np_images',  
        'posts_per_page' => 5  
    );  

    $result = '<section id="slideshow" class="theme-default">';  
    $result .= '<div class="nivoSlider inner">';  
  
    // The Query
    $loop = new WP_Query($args);  

    // The Loop
    while ($loop->have_posts()) {  
        $loop->the_post();  
        $the_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $type);  
        $result .='<img title="'.get_the_title().'" src="' . $the_url[0] . '" alt=""/>'; 
    }  


    $result .= '</div>';   
    $result .='</section>';  
    return $result;  
}  

add_shortcode('np-shortcode', 'np_function');  

?>