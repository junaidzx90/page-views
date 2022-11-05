<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Page_Views
 * @subpackage Page_Views/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Page_Views
 * @subpackage Page_Views/public
 * @author     Developer Junayed <admin@easeare.com>
 */
class Page_Views_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/page-views-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/page-views-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'pv_ajax', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'timer' => get_option('pv_waiting_time')
		) );
	}

	function get_next_page_id($pages, $currId){
		$nextPage = null;

		if(isset($_COOKIE['page_views_ids'])){
			$cookies = $_COOKIE['page_views_ids'];
			$cookies = unserialize(base64_decode($cookies));
			if(!is_array($cookies)){
				$cookies = [];
			}
			$cookies = array_values($cookies);

			$pages = get_option( 'pv_visitable_pages' );
			$pages = explode(",", $pages);
			if(!is_array($pages)){
				$pages = [];
			}
			
			$diffrent = array_diff($pages, $cookies);
			$diffrent = array_values($diffrent);
			if(sizeof($diffrent)>0){
				$nextPage = $diffrent[0];
			}else{
				$nextPage = false;
			}
		}

		if($nextPage === null){
			foreach($pages as $page){
				if($currId !== $page){
					$nextPage = $page;
				}
			}
		}

		return $nextPage;
	}

	function generateRandomString($length = 6) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		$randomString = base64_encode($randomString);
		if(!isset($_COOKIE['pv_rand_str'])){
			setcookie('pv_rand_str', $randomString, strtotime('tomorrow'), '/');
		}
	}

	function posts_the_content($the_content){
		global $post;
		if($post->post_type === 'post'){
			$pages = get_option( 'pv_visitable_pages' );
			$pages = explode(",", $pages);
			if(!is_array($pages)){
				$pages = [];
			}

			if(in_array($post->ID, $pages)){
				if(isset($_COOKIE['page_views_ids']) && isset($_COOKIE['pv_next_page'])){
					if(intval($_COOKIE['pv_next_page']) === $post->ID){
						$cookies = $_COOKIE['page_views_ids'];
						$cookies = unserialize(base64_decode($cookies));
						if(!is_array($cookies)){
							$cookies = [];
						}
	
						$cookies[intval($post->ID)] = strval($post->ID);
	
						$cookies = serialize($cookies);
						$cookies = base64_encode($cookies);
						setcookie('page_views_ids', $cookies, strtotime('tomorrow'), '/');
					}
				}else{
					$cookies[intval($post->ID)] = strval($post->ID);

					$cookies = serialize($cookies);
					$cookies = base64_encode($cookies);

					setcookie('page_views_ids', $cookies, strtotime('tomorrow'), '/');
				}

				$nextPage = $this->get_next_page_id($pages, $post->ID);
				if(intval($nextPage) === $post->ID){
					wp_safe_redirect( get_the_permalink(  ) );
					exit;
				}

				$counts = ((isset($_COOKIE['page_views_ids']))?$_COOKIE['page_views_ids']: '');
				$counts = unserialize(base64_decode($counts));
				if(!is_array($counts)){
					$counts = [];
				}
				$counts = sizeof($counts);
				
				$pvWrapper = '<div class="pv_wrapper">';
				$pvWrapper .= '<div class="pv_views">';
				$pvWrapper .= '<strong>You visited: <span class="visitCount">'.$counts.'</span> '.(($counts > 1)? 'pages': 'page').'</strong>';
				$pvWrapper .= '</div>';

				if($nextPage){
					$pvWrapper .= '<div class="pv_timer"></div>';
					$pvWrapper .= '<button class="pv_nextpage" data-id="'.$post->ID.'" data-next="'.$nextPage.'">See the next page to get the CODE</button>';
				}else{
					$code = null;
					if(isset($_COOKIE['pv_rand_str'])){
						$code = base64_decode($_COOKIE['pv_rand_str']);
					}
					$pvWrapper .= "<strong>Here is your code:</strong> <code>$code</code>";
				}

				$pvWrapper .= '</div>';
			}
		}
		
		return $the_content.$pvWrapper;
	}

	function send_to_the_next_page(){
		if(isset($_GET['data'])){
			$next_page = $_GET['data']['next'];
			setcookie('pv_next_page', $next_page, strtotime('tomorrow'), '/');
			$this->generateRandomString(6);
			echo json_encode(array("success" => get_the_permalink( $next_page )));
			die;
		}
	}
}
