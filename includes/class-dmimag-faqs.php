<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://faqs.dmimag.site
 * @since      1.0.0
 *
 * @package    Dmimag_Faqs
 * @subpackage Dmimag_Faqs/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dmimag_Faqs
 * @subpackage Dmimag_Faqs/includes
 * @author     dmimag <support.plugins@dmimag.site>
 */
class Dmimag_Faqs {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dmimag_Faqs_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
  
  /**
	 * Custom post type of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $post_type
	 */
	protected $post_type;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
    
		if ( defined( 'DMIMAG_FAQS_VERSION' ) ) {
      
			$this->version = DMIMAG_FAQS_VERSION;
      
		} else {
      
			$this->version = '1.1.1';
      
		}
    
		$this->plugin_name = 'dmimag-faqs';
    
    $this->post_type = 'dmimag-faqsq';

		$this->load_dependencies();
    
		$this->set_locale();
    
		$this->define_admin_hooks();
    
		$this->define_public_hooks();
    
    $this->define_update_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dmimag_Faqs_Loader. Orchestrates the hooks of the plugin.
	 * - Dmimag_Faqs_i18n. Defines internationalization functionality.
	 * - Dmimag_Faqs_Admin. Defines all hooks for the admin area.
	 * - Dmimag_Faqs_Public. Defines all hooks for the public side of the site.
   * - Dmimag_Faqs_Post_Types. Register post types and taxonomy  the plugin
   * - Dmimag_Faqs_Update. Defines all hooks for the update and details the plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dmimag-faqs-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dmimag-faqs-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dmimag-faqs-admin.php';
    
    /**
     * Custom Post Types
     *
     * @link https://github.com/JoeSz/WordPress-Plugin-Boilerplate-Tutorial/blob/master/plugin-name/tutorials/custom_post_types.php
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dmimag-faqs-post-types.php';
    
    /**
     * Adding Postbox to Post Types
     * 
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dmimag-faqs-postbox.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dmimag-faqs-public.php';
    
    /**
     * Update plugin
     * 
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dmimag-faqs-update.php';

		$this->loader = new Dmimag_Faqs_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dmimag_Faqs_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Dmimag_Faqs_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Dmimag_Faqs_Admin( $this->get_plugin_name(), $this->get_version() );

    /**
     * Custom Post Types
     *
     */
    $plugin_post_types = new Dmimag_Faqs_Post_Types( $this->get_plugin_name(), $this->get_version(), $this->get_post_type() );
    
    $this->loader->add_action( 'init', $plugin_post_types, 'create_custom_post_type', 999 );
    
    /**
     * Adding postbox to FAQs
     *
     * @since    1.1.1
     *
     */
    $plugin_postbox = new Dmimag_Faqs_Postbox( $this->get_plugin_name(), $this->get_version(), $this->get_post_type() ); 
    
    
    /*
     * Handle POST
     *
     * @since    1.1.1
     *
     * @link https://wp-kama.ru/hook/wp_ajax_-action
     */
    $this->loader->add_action( 'wp_ajax_dmimag_faqs_add_postbox', $plugin_postbox, 'dmimag_faqs_render_postbox_html', 10, 3 );

    /**
     * Render postbox
     *
     * @since    1.1.1
     *
     */
    $this->loader->add_action( 'add_meta_boxes', $plugin_postbox, 'dmimag_faqs_create_postbox' );
    
    /**
     * Remove default metabox
     *
     * @since    1.1.1
     *
     */    
    $this->loader->add_action( 'admin_menu', $plugin_postbox, 'dmimag_faqs_remove_default_meta_box' );
    
    /**
     * Render text shortcode
     *
     * @since    1.1.1
     *
     */    
    $this->loader->add_action( 'edit_form_after_title', $plugin_postbox, 'dmimag_faqs_render_text_shortcode' );

    /**
     * Render button
     *
     * @since    1.1.1
     *
     */    
    $this->loader->add_action( 'edit_form_advanced', $plugin_postbox, 'dmimag_faqs_render_button_add' );
    
    /**
     * Save post box
     *
     * @since    1.1.1
     *
     */
    $this->loader->add_action( 'wp_insert_post_data', $plugin_postbox, 'dmimag_faqs_save_postbox', 10, 2 );

    /**
     * Add taxonomy columns
     *
     */
    #$this->loader->add_filter( 'manage_edit-dmimag-faqs-group_columns', $plugin_admin, 'dmimag_faqs_manage_edit_taxonomy_columns' );
    
    #$this->loader->add_filter( 'manage_dmimag-faqs-group_custom_column', $plugin_admin, 'dmimag_faqs_manage_taxonomy_custom_column', 10, 3 );
    
    /**
     * Add shortcode columns FAQs posts.
     *
     * @since    1.1.1
     *
     */
    $this->loader->add_filter( "manage_{$this->get_post_type()}_posts_columns", $plugin_admin, 'dmimag_faqs_manage_dmimag_faqs_posts_columns' );
    
    /**
	 * Render shortcode text FAQs posts.
	 *
	 * @since    1.1.1
	 */    
    $this->loader->add_action( "manage_{$this->get_post_type()}_posts_custom_column", $plugin_admin, 'dmimag_faqs_manage_dmimag_faqs_posts_custom_column', 10, 2 );
    
    /**
     * Enqueue admin style & scripts
     *
     */    
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
    
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_postbox, 'dmimag_faqs_wp_enqueue_editor' );
    
    $this->loader->add_action( 'admin_print_footer_scripts', $plugin_postbox, 'dmimag_faqs_wp_editor_initialize', 99 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Dmimag_Faqs_Public( $this->get_plugin_name(), $this->get_version(), $this->get_post_type() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
    
    /**
     * Shortcode
     *
     * @since    1.0.0
     *
     */    
    $this->loader->add_shortcode( 'dmimag-faqs', $plugin_public, 'dmimag_faqs_shortcode' );

	}
  
  /**
	 * Register update of the hooks
	 * of the plugin.
	 *
	 * @since    1.1.1
	 * @access   private
	 */
	public function define_update_hooks() {
    
    $dmimag_faqs_plugin_update = new Dmimag_Faqs_Update( $this->get_plugin_name(), $this->get_version() );
    
    $this->loader->add_filter( 'plugins_api', $dmimag_faqs_plugin_update, 'dmimag_faqs_plugin_info', 20, 3 );
    
    $this->loader->add_filter( 'site_transient_update_plugins', $dmimag_faqs_plugin_update, 'dmimag_faqs_update' );
    
    $this->loader->add_action( 'upgrader_process_complete', $dmimag_faqs_plugin_update, 'dmimag_faqs_purge', 10, 2 ); 
    
    $this->loader->add_filter( 'plugin_row_meta', $dmimag_faqs_plugin_update, 'dmimag_faqs_plugin_row_meta', 25, 4 );
    
  }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Dmimag_Faqs_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
  
  /**
	 * Retrieve the post_type of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    Custom post type of the plugin.
	 */
	public function get_post_type() {
		return $this->post_type;
	}

}
?>