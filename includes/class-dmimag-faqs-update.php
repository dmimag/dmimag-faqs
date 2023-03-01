<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dmimag.site
 * @since      1.0.1
 *
 * @package    Dmimag_Core
 * @subpackage Dmimag_Core/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dmimag_Core
 * @subpackage Dmimag_Core/includes
 * @author     dmimag <support.plugins@dmimag.site>
 */

class Dmimag_Faqs_Update {
  
  /**
	 * The ID of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

  /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.1
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

  private $cache_key;
  
  private $cache_allowed;  
  

	public function __construct( $plugin_name, $version ) {

    $this->version = $version;

    $this->plugin_slug = DMIMAG_FAQS_BASE_DIR; //plugin_basename( __DIR__ );

    $this->plugin_file = DMIMAG_FAQS_BASE_FILE; //plugin_basename( __FILE__ );

    $this->cache_key = 'dmimag_faqs_update';

    $this->cache_allowed = false;
  }

  public function dmimag_faqs_request_update(){

    $remote = get_transient( $this->cache_key );

    if( false === $remote || ! $this->cache_allowed ) {
      #'http://dmimag.local/downloads/dmimag-core/dmimag-core.json',
      $remote = wp_remote_get(
        'https://dmimag.site/downloads/dmimag-faqs/dmimag-faqs.json',
        array(
          'timeout' => 10,
          'headers' => array(
            'Accept' => 'application/json'
          )
        )
      );

      if(
        is_wp_error( $remote )
        || 200 !== wp_remote_retrieve_response_code( $remote )
        || empty( wp_remote_retrieve_body( $remote ) )
      ) {
        return false;
      }

      set_transient( $this->cache_key, $remote, DAY_IN_SECONDS ); //

    }

    $remote = json_decode( wp_remote_retrieve_body( $remote ) );

    return $remote;

  }


  function dmimag_faqs_plugin_info( $res, $action, $args ) {

    // do nothing if you're not getting plugin information right now
    if( 'plugin_information' !== $action ) {
      return $res;
    }

    // do nothing if it is not our plugin
    if( $this->plugin_slug !== $args->slug ) {
      return $res;
    }

    // get updates
    $remote = $this->dmimag_faqs_request_update();

    if( ! $remote ) {
      return $res;
    }

    $res = new stdClass();

    $res->name = $remote->name;
    $res->slug = $remote->slug;
    $res->version = $remote->version;
    $res->tested = $remote->tested;
    $res->requires = $remote->requires;
    $res->author = $remote->author;
    $res->author_profile = $remote->author_profile;
    $res->download_link = $remote->download_url;
    $res->trunk = $remote->download_url;
    $res->requires_php = $remote->requires_php;
    $res->last_updated = $remote->last_updated;

    $res->sections = array(
      'description' => $remote->sections->description,
      'installation' => $remote->sections->installation,
      'changelog' => $remote->sections->changelog
    );

    if( ! empty( $remote->banners ) ) {
      $res->banners = array(
        'low' => $remote->banners->low,
        'high' => $remote->banners->high
      );
    }

    return $res;

  }

  public function dmimag_faqs_update( $transient ) {

    if ( empty( $transient->checked ) ) {
      return $transient;
    }

    $remote = $this->dmimag_faqs_request_update();

    if(
      $remote
      && version_compare( $this->version, $remote->version, '<' )
      && version_compare( $remote->requires, get_bloginfo( 'version' ), '<=' )
      && version_compare( $remote->requires_php, PHP_VERSION, '<' )
    ) {
      $res = new stdClass();
      $res->slug = $this->plugin_slug;
      $res->plugin = $this->plugin_file;
      $res->new_version = $remote->version;
      $res->tested = $remote->tested;
      $res->package = $remote->download_url;

      $transient->response[ $res->plugin ] = $res;
    }

    return $transient;

  }

  public function dmimag_faqs_purge( $upgrader, $options ){

    if (
      $this->cache_allowed
      && 'update' === $options['action']
      && 'plugin' === $options[ 'type' ]
    ) {
      // just clean the cache when new plugin version is installed
      delete_transient( $this->cache_key );
    }

  }
  
  public function dmimag_faqs_plugin_row_meta( $links_array, $plugin_file_name, $plugin_data, $status ) {

    if ( $plugin_file_name == DMIMAG_FAQS_BASE_FILE ) {

      $links_array[] = sprintf(
        '<a href="%s" class="thickbox open-plugin-details-modal">%s</a>',
        add_query_arg(
          array(
            'tab' => 'plugin-information',
            'plugin' => DMIMAG_FAQS_BASE_DIR,
            'TB_iframe' => true,
            'width' => 772,
            'height' => 788
          ),
          admin_url( 'plugin-install.php' )
        ),
        __( 'View details' )
      );

    }

    return $links_array;

  }

}
?>