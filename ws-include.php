<?php
/**
 * Created by PhpStorm.
 * User: Suzon Khan
 * Date: 9/7/2015
 * Time: 6:20 PM
 */

//--------------- Adding Latest jQuery------------//
function wpb_wps_jquery() {
    wp_enqueue_script('jquery');
}
add_action('init', 'wpb_wps_jquery');


//-------------- include js files---------------//
function wpb_wps_adding_scripts() {
    if (!is_admin()) {

        wp_register_script('carousel', plugins_url('js/scripts.js', __FILE__),array('jquery'),'1.3.2', true);
        wp_register_script('plugin-main', plugins_url('js/jquery.bxslider.min.js', __FILE__),array('jquery'),'1.0', true);
        wp_enqueue_script('carousel');
        wp_enqueue_script('plugin-main');
    }
}
add_action( 'wp_enqueue_scripts', 'wpb_wps_adding_scripts' );


//------------ include css files-----------------//
function wpb_wps_adding_style() {
    if (!is_admin()) {
        wp_register_style('wpb_wps_default', plugins_url('css/jquery.bxslider.css', __FILE__),'','1.0', false);
        wp_enqueue_style('wpb_wps_default');

    }
}
add_action( 'init', 'wpb_wps_adding_style' );