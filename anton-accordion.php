<?php

/**
 * Plugin Name: Anton Accordion
 * Plugin URI: https://www.anthonycarbon.com/
 * Description: Anton Accordion is suit for your accordion needs.
 * Text Domain: anton-accordion
 * Version: 1.0.8
 * Author: <a href="https://www.anthonycarbon.com/">Anthony Carbon</a>
 * Author URI: https://www.anthonycarbon.com/
 * Donate link: https://www.paypal.me/anthonypagaycarbon
 * Tags: accordion, responsive, jquery, javascript, animation, anthonycarbon.com
 * Requires at least: 4.4
 * Tested up to: 5.0
 * Stable tag: 1.0.8

 *
 * Text Domain: anton-accordion
 * Domain Path: /i18n/languages/
 *
 * @package Anton Accordion
 * @category Core
 * @author Anthony Carbon
 */

if ( ! defined( 'ABSPATH' ) ){ exit; }

if ( ! class_exists( 'Anton_Accordion' ) ) :

class Anton_Accordion {
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}
	private function define_constants() {
		$this->define( 'ANTON_ACCORDION', 'anton-accordion' );
		$this->define( 'ANTON_ACCORDION_NAME', 'Anton Accordions' );
		$this->define( 'ANTON_ACCORDION_BN', plugin_basename( __FILE__ ) );
		$this->define( 'ANTON_ACCORDION_URL', plugin_dir_url(__FILE__) );
		$this->define( 'ANTON_ACCORDION_IMG_URL', ANTON_ACCORDION_URL . 'assets/images' );
		$this->define( 'ANTON_ACCORDION_JS_URL', ANTON_ACCORDION_URL . 'assets/js' );
		$this->define( 'ANTON_ACCORDION_CSS_URL', ANTON_ACCORDION_URL . 'assets/css' );
		// PATH
		$this->define( 'ANTON_ACCORDION_PATH', plugin_dir_path( __FILE__ ) );
		$this->define( 'ANTON_ACCORDION_LIB_PATH', ANTON_ACCORDION_PATH . 'lib' );
		$this->define( 'ANTON_ACCORDION_ADMIN_PATH', ANTON_ACCORDION_LIB_PATH . '/admin' );
		$this->define( 'ANTON_ACCORDION_CORE_PATH', ANTON_ACCORDION_LIB_PATH . '/core' );
		$this->define( 'ANTON_ACCORDION_FUNCTIONS_PATH', ANTON_ACCORDION_LIB_PATH . '/functions' );
		$this->define( 'ANTON_ACCORDION_TEMPLATE_PATH', ANTON_ACCORDION_LIB_PATH . '/templates' );
		// DIR
		$this->define( 'ANTON_ACCORDION_PARENT_THEME_DIR', get_template_directory() );
		$this->define( 'ANTON_ACCORDION_CHILD_THEME_DIR', get_stylesheet_directory() );
	}
	private function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'styles_scripts' ) );
		add_shortcode( 'anton-accordion', array( $this, 'anton_accordion' ) );
	}
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
	public function includes() {
		include_once( ANTON_ACCORDION_FUNCTIONS_PATH . '/development-functions.php' );
		include_once( ANTON_ACCORDION_ADMIN_PATH . '/post-type.php' );
	}
	public function styles_scripts(){
		// styles
		wp_register_style( ANTON_ACCORDION . '-style', ANTON_ACCORDION_CSS_URL . '/style.min.css' );
		// scripts
		wp_register_script( ANTON_ACCORDION . '-script', ANTON_ACCORDION_JS_URL .'/script.min.js', array( 'jquery' ), false );
	}
	public function anton_accordion( $atts ){
		wp_enqueue_style( ANTON_ACCORDION . '-style' );
		wp_enqueue_script( ANTON_ACCORDION . '-script' );
		$a = shortcode_atts( array(
			'cat' => '',
			'active' => 0,
			'toggle' => 0,
			'minus' => 90,
			'scrolltop' => 1,
			'orderby' => 'date',
			'order' => 'DESC'
		), $atts );
		$query_args = array(
			'post_type'           => 'anton_accordion',
			'post_status'         => 'publish',
			'orderby' => $a['orderby'],
			'order' => $a['order'],
			'showposts'	=> -1,
		);
		if( $a['cat'] ) :
		 	$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'anton_accordions',	
					'field' => 'term_id',
					'terms' => $a['cat'],
				),
			);
		endif;
		$query = new WP_Query( $query_args );
		ob_start();
		if ( $query->have_posts() ) :
			wp_enqueue_style( 'dashicons' );
			echo '<div id="anton-accordion">';
			while ( $query->have_posts() ) :
				$query->the_post();
				$toggleclass = ( $a['active'] && ( $query->current_post == 0 ) ) ? 'anton-accordion-active' : '';
				printf( '<h4 class="anton-accordion-h4 %s" data-scrolltop="%s" data-toggle="%s" data-minus="%s">%s</h4>', $toggleclass, $a['toggle'], $a['scrolltop'], $a['minus'], get_the_title() );
				$style = ( $a['active'] && ( $query->current_post == 0 ) ) ? '' : ' style="display:none;width: 100%;min"';
				?><div class="anton-accordion-toggle-content"<?php echo $style; ?>><div class="entry"><?php the_content(); ?></div></div><?php
			endwhile;
			echo '</div>';
		endif;
		wp_reset_postdata();
		return ob_get_clean();
	}
}

new Anton_Accordion;

endif;