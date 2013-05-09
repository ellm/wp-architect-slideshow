<?php  
/* 
    Plugin Name: wp-Architect Homepage Slideshow  
    Description: Homepage Slideshow for WP-Architect Theme
    Author: Matthew Ell 
    Version: 1.0 
*/  

// Creates Admin Menu "Slides" Custom Post type

function wp_arch_ss_init() {  
    
    $args = array(  
        'public' => true,  
        'labels' => array(
            'name' => _x('Slides', 'post type general name'),
            'singular_name' => _x('Slide', 'post type singular name'),
            'add_new' => _x('Add New', 'event'),
            'add_new_item' => __('Add New Slide'),
            'edit_item' => __('Edit Slide'),
            'new_item' => __('New Slide'),
            'view_item' => __('View Slide'),
            'search_items' => __('Search Slide'),
            'not_found' =>  __('No events found'),
            'not_found_in_trash' => __('No events found in Trash'), 
            'parent_item_colon' => ''
        ),
        'description'  => 'Nivo Homepage Slideshow',
        'exclude_from_search' => true,
        'supports' => array(  
            'title',  
            'thumbnail'
        )
    ); 
    // http://codex.wordpress.org/Function_Reference/register_post_type
    register_post_type('np_images', $args);
}  

add_action('init', 'wp_arch_ss_init');


// Creates Admin Options sub-page
//http://codex.wordpress.org/Function_Reference/add_submenu_page

// add_action('admin_menu', 'register_my_custom_submenu_page');

// function register_my_custom_submenu_page() {
//     add_submenu_page( 'edit.php?post_type=np_images', 'Settings', 'Settings', 'manage_options', 'my-custom-submenu-page', 'my_custom_submenu_page_callback' ); 
// }

// function my_custom_submenu_page_callback() {
//     echo '<div class="wrap">';
//     echo '<h2>Slideshow Settings</h2>';
//     echo '</div>';

// }

// Creates Tables for Settings Pages
// http://codex.wordpress.org/Creating_Tables_with_Plugins

//     global $archss_db_version;
//     $archss_db_version = "1.0";

//     function archss_install() {
//         global $wpdb;
//         global $archss_db_version;

//         $table_name = $wpdb->prefix . "archss";
      
//         $sql = "CREATE TABLE $table_name (
//             id mediumint(9) NOT NULL AUTO_INCREMENT,
//             time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
//             name tinytext NOT NULL,
//             text text NOT NULL,
//             url VARCHAR(55) DEFAULT '' NOT NULL,
//             UNIQUE KEY id (id)
//         );";

//         require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
//         dbDelta( $sql );
 
//     add_option( "archss_db_version", $archss_db_version );
// }

//     function archss_install_data() {
//         global $wpdb;
//         $welcome_name = "Mr. WordPress";
//         $welcome_text = "Congratulations, you just completed the installation!";

//         $rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $welcome_name, 'text' => $welcome_text ) );
//     }

// register_activation_hook( __FILE__, 'archss_install' );
// register_activation_hook( __FILE__, 'archss_install_data' );

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
function np_function( $atts) { 

    // extract( shortcode_atts ( array(
    //     'width' => '',
    //     'height' => '',
    //     ), $atts ) ); 
    
    $args = array(  
        'post_type' => 'np_images',  
        'posts_per_page' => 5 
    );  

    $result = '<section id="slideshow" class="theme-default">';  
    $result .= '<div class="nivoSlider inner">';
  
    // The Query
    //http://codex.wordpress.org/Function_Reference/WP_Query
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