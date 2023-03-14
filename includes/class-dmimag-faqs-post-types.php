<?php
/**
 * Register custom post type
 *
 * @link       https://faqs.dmimag.site
 * @since      1.0.0
 * @package    Dmimag_Faqs
 * @subpackage Dmimag_Faqs/includes
 * @author     dmimag <support.plugins@dmimag.site>
 */
class Dmimag_Faqs_Post_Types {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Custom post type of the plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $post_type
   */
  private $post_type;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version, $post_type ) {

    $this->plugin_name = $plugin_name;

    $this->version = $version;   

    $this->post_type = $post_type; 

  }

  /**
   * Register custom post type
   *
   * @since    1.0.0
   */
  private function register_single_post_type( $fields ) {

    /**
     * Labels used when displaying the posts in the admin and sometimes on the front end.  These
     * labels do not cover post updated, error, and related messages.  You'll need to filter the
     * 'post_updated_messages' hook to customize those.
     */
    $labels = array(
      'name'                  => $fields['plural'],
      'singular_name'         => $fields['singular'],
      'menu_name'             => $fields['menu_name'],
      'new_item'              => sprintf( __( 'New %s', $this->plugin_name ), $fields['singular'] ),
      'add_new_item'          => sprintf( __( 'Add new %s', $this->plugin_name ), $fields['singular'] ),
      'edit_item'             => sprintf( __( 'Edit %s', $this->plugin_name ), $fields['singular'] ),
      'view_item'             => sprintf( __( 'View %s', $this->plugin_name ), $fields['singular'] ),
      'view_items'            => sprintf( __( 'View %s', $this->plugin_name ), $fields['plural'] ),
      'search_items'          => sprintf( __( 'Search %s', $this->plugin_name ), $fields['plural'] ),
      'not_found'             => sprintf( __( 'No %s found', $this->plugin_name ), strtolower( $fields['plural'] ) ),
      'not_found_in_trash'    => sprintf( __( 'No %s found in trash', $this->plugin_name ), strtolower( $fields['plural'] ) ),
      'all_items'             => sprintf( __( 'All %s', $this->plugin_name ), $fields['plural'] ),
      'archives'              => sprintf( __( '%s Archives', $this->plugin_name ), $fields['singular'] ),
      'attributes'            => sprintf( __( '%s Attributes', $this->plugin_name ), $fields['singular'] ),
      'insert_into_item'      => sprintf( __( 'Insert into %s', $this->plugin_name ), strtolower( $fields['singular'] ) ),
      'uploaded_to_this_item' => sprintf( __( 'Uploaded to this %s', $this->plugin_name ), strtolower( $fields['singular'] ) ),

      /* Labels for hierarchical post types only. */
      'parent_item'           => sprintf( __( 'Parent %s', $this->plugin_name ), $fields['singular'] ),
      'parent_item_colon'     => sprintf( __( 'Parent %s:', $this->plugin_name ), $fields['singular'] ),

      /* Custom archive label.  Must filter 'post_type_archive_title' to use. */
      'archive_title'        => $fields['plural'],
    );

    $args = array(
      'labels'             => $labels,
      'description'        => ( isset( $fields['description'] ) ) ? $fields['description'] : '',
      'public'             => ( isset( $fields['public'] ) ) ? $fields['public'] : true,
      'publicly_queryable' => ( isset( $fields['publicly_queryable'] ) ) ? $fields['publicly_queryable'] : true,
      'exclude_from_search'=> ( isset( $fields['exclude_from_search'] ) ) ? $fields['exclude_from_search'] : false,
      'show_ui'            => ( isset( $fields['show_ui'] ) ) ? $fields['show_ui'] : true,
      'show_in_menu'       => ( isset( $fields['show_in_menu'] ) ) ? $fields['show_in_menu'] : true,
      'query_var'          => ( isset( $fields['query_var'] ) ) ? $fields['query_var'] : true,
      
      'show_in_admin_bar'  => ( isset( $fields['show_in_admin_bar'] ) ) ? $fields['show_in_admin_bar'] : true,
      'capability_type'    => ( isset( $fields['capability_type'] ) ) ? $fields['capability_type'] : 'post',
      'has_archive'        => ( isset( $fields['has_archive'] ) ) ? $fields['has_archive'] : true,
      'hierarchical'       => ( isset( $fields['hierarchical'] ) ) ? $fields['hierarchical'] : true,
      'supports'           => ( isset( $fields['supports'] ) ) ? $fields['supports'] : array(
        'title',
        'editor',
        'excerpt',
        'author',
        'thumbnail',
        'comments',
        'trackbacks',
        'custom-fields',
        'revisions',
        'page-attributes',
        'post-formats',
      ),
      'menu_position'      => ( isset( $fields['menu_position'] ) ) ? $fields['menu_position'] : 21,
      'menu_icon'          => ( isset( $fields['menu_icon'] ) ) ? $fields['menu_icon']: 'dashicons-admin-generic',
      'show_in_nav_menus'  => ( isset( $fields['show_in_nav_menus'] ) ) ? $fields['show_in_nav_menus'] : true,
    );

    if ( isset( $fields['rewrite'] ) ) {

      /**
       *  Add $this->plugin_name as translatable in the permalink structure,
       *  to avoid conflicts with other plugins which may use customers as well.
       */
      $args['rewrite'] = $fields['rewrite'];
    }

    register_post_type( $fields['slug'], $args );

    /**
     * Register Taxnonmies if any
     * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
     */
    if ( isset( $fields['taxonomies'] ) && is_array( $fields['taxonomies'] ) ) {

      foreach ( $fields['taxonomies'] as $taxonomy ) {

        $this->register_single_post_type_taxnonomy( $taxonomy );

      }

    }

  }

  private function register_single_post_type_taxnonomy( $tax_fields ) {

    $labels = array(
      'name'                       => $tax_fields['plural'],
      'singular_name'              => $tax_fields['single'],
      'menu_name'                  => $tax_fields['plural'],
      'all_items'                  => sprintf( __( 'All %s' , $this->plugin_name ), $tax_fields['plural'] ),
      'edit_item'                  => sprintf( __( 'Edit %s' , $this->plugin_name ), $tax_fields['single'] ),
      'view_item'                  => sprintf( __( 'View %s' , $this->plugin_name ), $tax_fields['single'] ),
      'update_item'                => sprintf( __( 'Update %s' , $this->plugin_name ), $tax_fields['single'] ),
      'add_new_item'               => sprintf( __( 'Add New %s' , $this->plugin_name ), $tax_fields['single'] ),
      'new_item_name'              => sprintf( __( 'New %s Name' , $this->plugin_name ), $tax_fields['single'] ),
      'parent_item'                => sprintf( __( 'Parent %s' , $this->plugin_name ), $tax_fields['single'] ),
      'parent_item_colon'          => sprintf( __( 'Parent %s:' , $this->plugin_name ), $tax_fields['single'] ),
      'search_items'               => sprintf( __( 'Search %s' , $this->plugin_name ), $tax_fields['plural'] ),
      'popular_items'              => sprintf( __( 'Popular %s' , $this->plugin_name ), $tax_fields['plural'] ),
      'separate_items_with_commas' => sprintf( __( 'Separate %s with commas' , $this->plugin_name ), $tax_fields['plural'] ),
      'add_or_remove_items'        => sprintf( __( 'Add or remove %s' , $this->plugin_name ), $tax_fields['plural'] ),
      'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s' , $this->plugin_name ), $tax_fields['plural'] ),
      'not_found'                  => sprintf( __( 'No %s found' , $this->plugin_name ), $tax_fields['plural'] ),
    );

    $args = array(
      'label'                 => $tax_fields['plural'],
      'labels'                => $labels,
      'hierarchical'          => ( isset( $tax_fields['hierarchical'] ) )          ? $tax_fields['hierarchical']          : true,
      'public'                => ( isset( $tax_fields['public'] ) )                ? $tax_fields['public']                : true,
      'publicly_queryable'    => ( isset( $tax_fields['public'] ) )                ? $tax_fields['publicly_queryable']    : true,
      'show_ui'               => ( isset( $tax_fields['show_ui'] ) )               ? $tax_fields['show_ui']               : true,
      'show_in_nav_menus'     => ( isset( $tax_fields['show_in_nav_menus'] ) )     ? $tax_fields['show_in_nav_menus']     : true,
      'show_tagcloud'         => ( isset( $tax_fields['show_tagcloud'] ) )         ? $tax_fields['show_tagcloud']         : true,
      'meta_box_cb'           => ( isset( $tax_fields['meta_box_cb'] ) )           ? $tax_fields['meta_box_cb']           : null,
      'show_admin_column'     => ( isset( $tax_fields['show_admin_column'] ) )     ? $tax_fields['show_admin_column']     : true,
      'show_in_quick_edit'    => ( isset( $tax_fields['show_in_quick_edit'] ) )    ? $tax_fields['show_in_quick_edit']    : true,
      'update_count_callback' => ( isset( $tax_fields['update_count_callback'] ) ) ? $tax_fields['update_count_callback'] : '',
      #'show_in_rest'          => ( isset( $tax_fields['show_in_rest'] ) )          ? $tax_fields['show_in_rest']          : true,
      #'rest_base'             => $tax_fields['taxonomy'],
      #'rest_controller_class' => ( isset( $tax_fields['rest_controller_class'] ) ) ? $tax_fields['rest_controller_class'] : 'WP_REST_Terms_Controller',
      'query_var'             => ( isset( $tax_fields['query_var'] ) )          ? $tax_fields['query_var']          : true,
      'rewrite'               => ( isset( $tax_fields['rewrite'] ) )               ? $tax_fields['rewrite']               : true,
      'sort'                  => ( isset( $tax_fields['sort'] ) )                  ? $tax_fields['sort']                  : '',
    );

    $args = apply_filters( $tax_fields['taxonomy'] . '_args', $args );

    register_taxonomy( $tax_fields['taxonomy'], $tax_fields['post_types'], $args );

  }

  /**
   * Assign capabilities to users
   *
   */
  public function assign_capabilities( $caps_map, $users  ) {
    foreach ( $users as $user ) {

      $user_role = get_role( $user );

      foreach ( $caps_map as $cap_map_key => $capability ) {

        $user_role->add_cap( $capability );

      }

    }
  }

  /**
   * Create post types
   *
   */
  public function create_custom_post_type() {

    $post_types_fields = array(
      array(
        'slug'                  => $this->post_type,
        'singular'              => __('FAQ', $this->plugin_name),
        'plural'                => __('FAQs', $this->plugin_name),
        'menu_name'             => __('FAQs', $this->plugin_name),
        'description'           => __('FAQs', $this->plugin_name),
        'has_archive'           => false,
        'hierarchical'          => false,
        'menu_icon'             => 'dashicons-media-text',
        'menu_position'         => 21,        
        'public'                => false,
        'publicly_queryable'    => false,        
        'exclude_from_search'   => true,        
        'show_ui'               => true,
        'show_in_menu'          => true,        
        'query_var'             => false,        
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'supports'              => array(
          'title'
        )
      )
    );

    foreach ( $post_types_fields as $fields ) {
      $this->register_single_post_type( $fields );
    }
  }
}
?>