<?php
/*
Plugin Name: Widget Credit Card
Plugin URI: https://github.com/marin73tomas/
Description: Test plugin card
Version: 1.1.0
Author: Tomas
License: 
*/
function wp_create_widget_custom(){
    include_once(dirname( __FILE__ ) . '/includes/CustomWidget.php');

    registerStyle();

    register_widget('CustomWidget');
}

function registerStyle(){
    wp_enqueue_style(
        'custom-widget',
        plugin_dir_url(__FILE__) . 'assets/style.css',
        array(),
        null, 
    );
}

add_action('widgets_init', 'wp_create_widget_custom');

