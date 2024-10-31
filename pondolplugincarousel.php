<?php
/*
Plugin Name: Pondol Carousel
Plugin URI: http://www.shop-wiz.com
Description: WordPress Image Carousel Plugin
Version: 1.0
Author: Pondol
Author URI: http://www.shop-wiz.com
License: Copyright 2014 Pondol, All Rights Reserved
*/

define('PONDOLPLUGIN_CAROUSEL_VERSION', '1.0');
define('PONDOLPLUGIN_CAROUSEL_URL', plugin_dir_url( __FILE__ ));
define('PONDOLPLUGIN_CAROUSEL_PATH', plugin_dir_path( __FILE__ ));

require_once 'includes/class.pondolplugin.carousel.php';

class PondolPlugin_Carousel_Plugin {
	
	function __construct() {
	
		$this->init();
	}
	
	public function init() {
		
		// init controller
		$this->carousel = new PondolPlugin_Carousel();
		
		add_action( 'admin_menu', array($this, 'register_menu') );
		
		add_shortcode( 'pondolplugin_carousel', array($this, 'shortcode_handler') );
		
		//add_action( 'wp_footer', array($this, 'add_lightbox_options') );
		add_action( 'init', array($this, 'register_script') );
		//add_action( 'wp_enqueue_scripts', array($this, 'enqueue_script') );
		
		if ( is_admin() )
		{
			add_action( 'wp_ajax_pondolplugin_carousel_save_item', array($this, 'wp_ajax_save_item') );
			add_action( 'admin_init', array($this, 'admin_init_hook') );
		}
	}
	
	//admin regis menu
	function register_menu()
	{
		$menu = add_menu_page(
				__('PondolPlugin Carousel', 'pondolplugin_carousel'),
				__('PondolPlugin Carousel', 'pondolplugin_carousel'),
				'manage_options',
				'pondolplugin_carousel_overview',
				array($this, 'show_overview'),
				PONDOLPLUGIN_CAROUSEL_URL . 'assets/images/pondol-16.png' );
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'pondolplugin_carousel_overview',
				__('Overview', 'pondolplugin_carousel'),
				__('Overview', 'pondolplugin_carousel'),
				'manage_options',
				'pondolplugin_carousel_overview',
				array($this, 'show_overview' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'pondolplugin_carousel_overview',
				__('New Carousel', 'pondolplugin_carousel'),
				__('New Carousel', 'pondolplugin_carousel'),
				'manage_options',
				'pondolplugin_carousel_add_new',
				array($this, 'add_new' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'pondolplugin_carousel_overview',
				__('Manage Carousels', 'pondolplugin_carousel'),
				__('Manage Carousels', 'pondolplugin_carousel'),
				'manage_options',
				'pondolplugin_carousel_show_items',
				array($this->carousel, 'show_items' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				null,
				__('View Carousel', 'pondolplugin_carousel'),
				__('View Carousel', 'pondolplugin_carousel'),	
				'manage_options',	
				'pondolplugin_carousel_show_item',	
				array($this->carousel, 'show_item' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				null,
				__('Edit Carousel', 'pondolplugin_carousel'),
				__('Edit Carousel', 'pondolplugin_carousel'),
				'manage_options',
				'pondolplugin_carousel_edit_item',
				array($this, 'edit_item' ) );
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );

	}
	
	function register_script()
	{		
		wp_register_script('pondolplugin-carousel-creator-script', PONDOLPLUGIN_CAROUSEL_URL . 'assets/js/pondolplugin-carousel-creator.js', array('jquery'), PONDOLPLUGIN_CAROUSEL_VERSION, false);
		wp_register_style('pondolplugin-carousel-admin-style', PONDOLPLUGIN_CAROUSEL_URL . 'assets/css/pondolplugincarousel_admin.css');
		wp_register_style('pondolplugin-carousel-display-style', PONDOLPLUGIN_CAROUSEL_URL . 'assets/css/pondolplugincarousel_display.css');
	}
	
	function enqueue_script()
	{

	}
	
	function enqueue_admin_script($hook)
	{
		wp_enqueue_script('post');
		wp_enqueue_script('pondolplugin-carousel-template-script');
		wp_enqueue_script('pondolplugin-carousel-skins-script');
		wp_enqueue_script('pondolplugin-carousel-script');
		wp_enqueue_script('pondolplugin-carousel-creator-script');
		wp_enqueue_style('pondolplugin-carousel-admin-style');
	}

	function admin_init_hook()
	{
		// change text of history media uploader
		if (!function_exists("wp_enqueue_media"))
		{
			global $pagenow;
			
			if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
				add_filter( 'gettext', array($this, 'replace_thickbox_text' ), 1, 3 );
			}
		}
		
		// add meta boxes
		//$this->carousel->add_metaboxes();
	}
	
	function replace_thickbox_text($translated_text, $text, $domain) {
		
		if ('Insert into Post' == $text) {
			$referer = strpos( wp_get_referer(), 'pondolplugin-carousel' );
			if ( $referer != '' ) {
				return __('Insert into carousel', 'pondolplugin_carousel' );
			}
		}
		return $translated_text;
	}
	
	function show_overview() {
		
		$this->carousel->show_overview();
	}
	

	
	function add_new() {
		
		$this->carousel->add_new();
	}
	

	
	function edit_item() {
	
		$this->carousel->edit_item();
	}
	
	function shortcode_handler($atts) {
		
		if ( !isset($atts['id']) )
			return __('Please specify a carousel id', 'pondolplugin_carousel');
		
		return $this->carousel->generate_body_code( $atts['id'], false);
	}
	
	function wp_ajax_save_item() {
				
		header('Content-Type: application/json');
		echo json_encode($this->carousel->save_item($_POST["item"]));
		die();
	}
	
}

/**
 * Init the plugin
 */
$pondolplugin_carousel_plugin = new PondolPlugin_Carousel_Plugin();

/**
 * Global php function
 * @param $id
 */
function pondolplugin_carousel($id) {

	echo $pondolplugin_carousel_plugin->controller->generate_body_code($id, false);
}
