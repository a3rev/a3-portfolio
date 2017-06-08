<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class A3_Portfolio_Cookies
{

	public function __construct() {
		// AJAX set cookie Portfolio Recently Viewed
		add_action( 'wp_ajax_a3_portfolio_set_cookie', array( $this, 'a3_portfolio_set_cookie' ) );
		add_action( 'wp_ajax_nopriv_a3_portfolio_set_cookie', array( $this, 'a3_portfolio_set_cookie' ) );
		add_action( 'wp_ajax_a3_portfolio_remove_all_cookie', array( $this, 'a3_portfolio_remove_all_cookie' ) );
		add_action( 'wp_ajax_nopriv_a3_portfolio_remove_all_cookie', array( $this, 'a3_portfolio_remove_all_cookie' ) );
		add_action( 'wp_ajax_a3_portfolio_remove_cookie', array( $this, 'a3_portfolio_remove_cookie' ) );
		add_action( 'wp_ajax_nopriv_a3_portfolio_remove_cookie', array( $this, 'a3_portfolio_remove_cookie' ) );
	}

	public function portfolio_cookies( $cookie_name, $portfolio_cookie_ID, $expire_day, $max_stored = 10000 ) {

		$portfolio_cookie_ID = (int)$portfolio_cookie_ID;

		$expire = strtotime('+'.$expire_day.' days');

		//$expire = time() + (86400 * $expire_day);

		$stored = 0;

		$skip = false;

		if ( array_key_exists( $cookie_name, $_COOKIE ) ) {
			$cookie = $_COOKIE[$cookie_name];
			$cookie = json_decode($cookie);
			$stored = count($cookie);
			if ( in_array( $portfolio_cookie_ID,$cookie ) ) {
				$skip = true;
			}
		} else {
			$cookie = array();
		}
		if ( $stored < $max_stored && $skip == false ) {
			$cookie[] = $portfolio_cookie_ID;
			$cookie = json_encode($cookie);
			@setcookie($cookie_name, $cookie, $expire, '/');
		} else if ( $skip == false ) {
			array_shift($cookie);
			$cookie[] = $portfolio_cookie_ID;
			$cookie = json_encode($cookie);
			@setcookie($cookie_name, $cookie, $expire, '/');
		}
	}

	public function remove_all_portfolio_cookies( $cookie_name = 'portfolio_recentviews') {
		unset($_COOKIE[$cookie_name]);
		$_COOKIE[$cookie_name] = '';
		// empty value and expiration one hour before
		$res = setcookie('portfolio_recentviews','', time() - 3600, '/');
	}

	public function remove_portfolio_cookies( $cookie_name = 'portfolio_recentviews', $portfolio_cookie_ID) {
		if(array_key_exists($cookie_name, $_COOKIE)) {
			$portfolio_cookie_ID = (int)$portfolio_cookie_ID;
			$cookie = $_COOKIE[$cookie_name];
			$cookie = json_decode($cookie);
			$new_cookie = array();
			if ( is_array($cookie) ) {
				foreach ( $cookie as $key=>$value ) {
					if ( $value != $portfolio_cookie_ID ) {
						$new_cookie[] = $value;
					}
				}
				$expire = time() + (86400 * 7);
				$new_cookie = json_encode($new_cookie);
				@setcookie($cookie_name, $new_cookie, $expire, '/');
			}

		}
	}

	public function a3_portfolio_set_cookie() {
		if ( isset( $_POST['portfolio_id'] ) ) {
			$portfolio_id = $_POST['portfolio_id'];
			$lang = $_POST['lang'];
			$this->portfolio_cookies( 'portfolio_recentviews' . $lang, $_POST['portfolio_id'], 7);
			echo 'true';
		} else {
			echo 'false';
		}
		die();
	}


	public function a3_portfolio_remove_all_cookie() {
		$lang = $_POST['lang'];
		$this->remove_all_portfolio_cookies( 'portfolio_recentviews' . $lang );
		echo 'true';
		die();
	}

	public function a3_portfolio_remove_cookie() {
		if ( isset( $_POST['portfolio_id'] ) ) {
			$portfolio_id = $_POST['portfolio_id'];
			$lang = $_POST['lang'];
			$this->remove_portfolio_cookies( 'portfolio_recentviews' . $lang, $portfolio_id );
			echo $portfolio_id;
		} else {
			echo 'false';
		}
		die();
	}
}

global $a3_portfolio_cookies;
$a3_portfolio_cookies = new A3_Portfolio_Cookies();
?>