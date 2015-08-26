<?php
/*
  Plugin Name: Cherry Lazy Load Boxes Plugin
  Version: 1.0
  Plugin URI: http://www.cherryframework.com/
  Description: Create blocks with lazy load effect
  Author: Cherry Team.
  Author URI: http://www.cherryframework.com/
  Text Domain: cherry-lazy-load
  Domain Path: languages/
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
if ( ! defined( 'ABSPATH' ) )
exit;

class cherry_lazy_load {
  
  public $version = '1.0';

  function __construct() {
    add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
    add_shortcode( 'lazy_load_box', array( $this, 'lazy_load_shortcode' ) );
  }

  function assets() {
    if ( is_singular() ) {
      wp_enqueue_script( 'cherry-lazy-load', $this->url('js/cherry.lazy-load.js'), array('jquery'), $this->version, true );
      wp_enqueue_script('device-check', $this->url('js/device.min.js'), array('jquery'), '1.0.0', true );
      wp_enqueue_style( 'cherry-lazy-load', $this->url('css/lazy-load.css'), '', $this->version );
    }
  }

  /**
   * return plugin url
   */
  function url( $path = null ) {
    $base_url = untrailingslashit( plugin_dir_url( __FILE__ ) );
    if ( !$path ) {
      return $base_url;
    } else {
      return esc_url( $base_url . '/' . $path );
    }
  }

  /**
   * return plugin dir
   */
  function dir( $path = null ) {
    $base_dir = untrailingslashit( plugin_dir_path( __FILE__ ) );
    if ( !$path ) {
      return $base_dir;
    } else {
      return esc_url( $base_dir . '/' . $path );
    }
  }

  /**
   * Shortcode
   */
  function lazy_load_shortcode( $atts, $content = null ) {
    extract(shortcode_atts( array(
        'effect'       => 'fade',
        'delay'        => '0',
        'speed'        => '600',
        'custom_class' => ''
      ), 
      $atts, 
      'lazy_load_box' 
    ));

    $default_css = '-webkit-transition: all ' . $speed . 'ms ease; -moz-transition: all ' . $speed . 'ms ease; -ms-transition: all ' . $speed . 'ms ease; -o-transition: all ' . $speed . 'ms ease; transition: all ' . $speed . 'ms ease;';
    $default_css = apply_filters( 'cherry_lazy_load_default_css', $default_css );
    $result = '<section class="lazy-load-box trigger effect-'  . esc_attr( $effect ) . ' ' . esc_attr( $custom_class ) . '" data-delay="' . $delay . '" data-speed="' . $speed . '" style="' . $default_css . '">';
      $result .= do_shortcode( $content );
    $result .= '</section>';

    return $result;
  }

}

new cherry_lazy_load();
?>