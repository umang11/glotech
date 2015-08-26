<?php
add_action( 'wp_enqueue_scripts', 'cherry_child_custom_scripts' );

function cherry_child_custom_scripts() {
	var_dump(CHILD_URL . '/js/parallaxSlider.js');
    wp_enqueue_script( 'parallaxSlider', get_stylesheet_directory_uri() . '/js/parallaxSlider.js', array('jquery'), '1.0' );
	/**
	 * How to enqueue script?
	 *
	 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	 */
} ?>

