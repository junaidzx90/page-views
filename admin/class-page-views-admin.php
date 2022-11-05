<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Page_Views
 * @subpackage Page_Views/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Page_Views
 * @subpackage Page_Views/admin
 * @author     Developer Junayed <admin@easeare.com>
 */
class Page_Views_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Page_Views_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Page_Views_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'selectize', plugin_dir_url( __FILE__ ) . 'css/selectize.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/page-views-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Page_Views_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Page_Views_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		wp_enqueue_script( "selectize", plugin_dir_url( __FILE__ ) . 'js/selectize.min.js', array(), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/page-views-admin.js', array( 'jquery', 'selectize' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'pageviews', array(
			'ajaxurl' => admin_url('admin-ajax.php')
		) );

	}

	function admin_menu_page(){
		add_menu_page( "Page views", "Page views", "manage_options", "page-views", [$this, "page_views_menu_page"], "dashicons-visibility", 45 );
		add_submenu_page("page-views", "Settings", "Settings", "manage_options", "vt-settings",[$this, "pv_settings"], null );

		add_settings_section( 'page_views_setting_section', '', '', 'page_views_setting_page' );
		// Waiting time in seconds
		add_settings_field( 'pv_waiting_time', 'Waiting time in milliseconds ', [$this, 'pv_waiting_time_cb'], 'page_views_setting_page','page_views_setting_section' );
		register_setting( 'page_views_setting_section', 'pv_waiting_time' );
		// Visitable pages
		add_settings_field( 'pv_visitable_pages', 'Visitable pages ', [$this, 'pv_visitable_pages_cb'], 'page_views_setting_page','page_views_setting_section' );
		register_setting( 'page_views_setting_section', 'pv_visitable_pages' );
	}

	function pv_waiting_time_cb(){
		echo '<input min="0" type="number" name="pv_waiting_time" value="'.get_option('pv_waiting_time').'" placeholder="500">';
	}

	function pv_visitable_pages_cb(){
		echo '<input type="text" id="visitable_pages" name="pv_visitable_pages" class="widefat">';
	}

	function get_saved_pages(){
		$pages = get_option('pv_visitable_pages');
		$pages = explode(",", $pages);
		if(!is_array($pages)){
			$pages = [];
		}
		$pagesArr = [];
		foreach($pages as $page){
			$pageArr = [
				'id'	=> $page,
				'title' => get_the_title( $page )
			];

			$pagesArr[] = $pageArr;
		}

		echo json_encode(array('success' => $pagesArr));
		die;
	}

	function pv_settings(){
		?>
		<h3>Settings</h3>
		<hr>

		<div class="page-views-settings" style="width: 90%">
            <form method="post" action="options.php">
                <?php
                settings_fields( 'page_views_setting_section' );
                do_settings_sections('page_views_setting_page');
                echo get_submit_button( 'Save Changes', 'primary', 'save-page-views-setting' );
                ?>
            </form>
        </div>
		<?php
	}

	function page_views_menu_page(){
		require_once plugin_dir_path(__FILE__ )."partials/page-views-admin-display.php";
	}

	function get_page_views_search_val(){
		if(isset($_GET['query'])){
			$query = sanitize_text_field($_GET['query'] );
			$query = stripslashes($query);

			$posts = get_posts([
				'post_type' => 'post',
				'numberposts' => -1,
				's' => $query
			]);

			$data = [];
			if($posts){
				foreach($posts as $post){
					$data[] = [
						'id' => $post->ID,
						'title' => $post->post_title
					];
				}
			}

			echo json_encode(array("success" => $data));
			die;
		}
	}

}
