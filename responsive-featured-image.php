<?php
/*
Plugin Name: Responsive Featured Images
Plugin URI: http://github.com/jcasabona/responsive-featured-image/
Description: This plugin adds a simple function that will replace WordPress' default featured image markup with something more responsive-friendly. 
Author: Joe Casabona
Version: 1.0
Author URI: http://casabona.org/
*/


define('RFI_PATH', plugin_dir_url(__FILE__));

if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
}

function rfi_script() {
  wp_register_script( 'picturefill', RFI_PATH . 'picturefill.js');
  wp_enqueue_script( 'picturefill' );
}

add_action( 'wp_enqueue_scripts', 'rfi_script' );

function rfi_get_featured_image($html, $aid=false){
    $sizes= array('thumbnail', 'medium', 'large', 'full');
    $img= '<span data-picture data-alt="'.get_the_title().'">';
    $ct= 0;
    $aid= (!$aid) ? get_post_thumbnail_id() : $aid;
    
    foreach($sizes as $size){
        $url= wp_get_attachment_image_src($aid, $size);
        $width= ($ct < sizeof($sizes)-1) ? ($url[1]*0.66) : ($width/0.66)+25;
        $img.= '<span data-src="'. $url[0] .'"';
        $img.= ($ct > 0) ? ' data-media="(min-width: '. $width .'px)"></span>' :'></span>';
        $ct++; 
    }
    
    $url= wp_get_attachment_image_src( $aid, $sizes[1]);
    $img.=  '<noscript>
                <img src="'.$url[0] .'" alt="'.get_the_title().'">
            </noscript>
    </span>';
    return $img;
}

add_filter( 'post_thumbnail_html', 'rfi_get_featured_image');

?>