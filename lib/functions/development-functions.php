<?php

/**
 * Development Functions
 * Revision 0.1.2
 **/
 
if ( ! defined( 'ABSPATH' ) ){ exit; }

if ( ! function_exists( 'wpcurrentuser' ) ){
	function wpcurrentuser( $key ) {
		global $current_user;
		if( is_array( wpkeyvalue( $current_user, $key ) ) ){
			return wpkeyvalue( $current_user, $key );
		}else if( ! empty( $current_user->$key ) ){
			return $current_user->$key;
		}
		return;
	}
}

if ( ! function_exists( 'wpcurlresults' ) ){
	function wpcurlresults( $url ) {
		if ( ! function_exists( 'curl_init' ) ){ 
			die('CURL is not installed!');
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $Url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}

if ( ! function_exists( 'wplatlng' ) ){
	function wpgeometry( $zip ){
		if( ! $zip ){ return; }
		$zip = str_replace( " ","+", $zip );
		$geocode = wpcurlresults( "http://maps.google.com/maps/api/geocode/json?address=$zip&sensor=false" );
		$results = json_decode( $geocode );
		$json = wpstdclass( $results, 'results' );
		$index_0 = wpstdclass( $json, 0 );
		if( ! isset( $json[0] ) ){ return; }
		return wpstdclass( $index_0, 'geometry' );
	}
}

if ( ! function_exists( 'wplatlng' ) ){
	function wplatlng( $zip, $type = 'lat' ){
		if( ! $zip ){ return; }
		$geometry = wpgeometry( $zip );
		$LatLong = wpstdclass( $geometry, 'location', 'lat' );
		if( $type == 'lng' ){
			$LatLong = wpstdclass( $geometry, 'location', 'lng' );
		}
		return $LatLong;
	}
}

if ( ! function_exists( 'wpbp' ) ){
	function wpbp( $key, $echo = false ){
		global $bp;
		if( empty( $bp->$key ) && ! $echo ){
			return;
		}
		if( $echo ){
			echo $bp->$key;
			return;
		}
		return $bp->$key;
	}
}

if ( ! function_exists( 'wpbploggedinuser' ) ){
	function wpbploggedinuser( $key, $echo = false ){
		global $bp;
		$loggedin_user = wpstdclass( $bp, 'loggedin_user' );
		$userdata = wpstdclass( $loggedin_user, 'userdata' );
		if( ! empty( $userdata->$key ) ){
			$loggedin_user = $userdata->$key;
		}
		if( $echo && ! empty( $loggedin_user->$key ) ){
			echo $loggedin_user->$key;
			return;
		}
		if( empty( $loggedin_user->$key ) ){
			return;
		}
		return $loggedin_user->$key;
	}
}

if ( ! function_exists( 'wpbpdisplayeduser' ) ){
	function wpbpdisplayeduser( $key, $echo = false ){
		global $bp;
		$displayed_user = wpstdclass( $bp, 'displayed_user' );
		$userdata = wpstdclass( $displayed_user, 'userdata' );
		if( ! empty( $userdata->$key ) ){
			$loggedin_user = $userdata->$key;
		}
		if( $echo && ! empty( $displayed_user->$key ) ){
			echo $displayed_user->$key;
			return;
		}
		if( empty( $displayed_user->$key ) ){
			return;
		}
		return $displayed_user->$key;
	}
}

if ( ! function_exists( 'wpjoin' ) ){
	function wpjoin( $array, $separator = ", ", $echo = false ){
		if( $echo == true ){
			echo join( "{$separator}", array_filter( (array) $array ) );
			return;
		}
	   	return join( "{$separator}", array_filter( (array) $array ) );
	}
}

if ( ! function_exists( 'wpstring' ) ){
	function wpstring( $string, $echo = false, $separator = "-" ){
		$string = strtolower( $string );
		$string = str_replace( ' ', '-', $string );
		$string = preg_replace( '/[^A-Za-z0-9\-]/', '', $string );
		if( $echo == true ){
			echo preg_replace( '/-+/', $separator, $string );
			return;
		}
	   	return preg_replace( '/-+/', $separator, $string );
	}
}

if ( ! function_exists( 'wpimgsrc' ) ){
	function wpimgsrc( $thumbnail_id = 0, $size = 'full', $index = 0  ){
		$image = array();
		if( $thumbnail_id ){
			$image = wp_get_attachment_image_src( $thumbnail_id, $size );
		}else{
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $size );
		}
		if( ! $image ){
			return;
		}
		if( $image ){
			return wpkeyvalue( $image, $index );
		}
		return;
	}
}

if ( ! function_exists( 'wpifisset' ) ){
	function wpifisset( $var, $key  ){
		if( isset( $var[$key] ) ){
			return true;
		}
		return false;
	}
}

if ( function_exists( 'wc' ) && ! function_exists( 'wcaddedtocart' ) ){
	function wcaddedtocart( $id ){
		$carts = wc()->cart->get_cart();
		if( $carts ){
			foreach( $carts as $cart ){
				if( $id == wpkeyvalue( $cart, 'product_id' ) ) {
					return true;
				}
			}
		}
		return false;
	}
}

if ( ! function_exists( 'wpserializetoarray' ) ){
	function wpserializetoarray( $key, $user_id = 0, $method = 'user' ){
		$user_meta = get_user_meta( get_current_user_id(), $key, true );
		if( $user_id ){
			$user_meta = get_user_meta( $user_id, $key, true );
		}
		$output = (array) $user_meta;
		return array_filter( $output );
	}
}

if ( ! function_exists( 'wpstdclass' ) ){
	function wpstdclass( $var, $level1, $level2 = false, $level3 = false ) {
		if( empty( $var->$level1 ) && !$level2 ){
			return;
		}
		if( empty( $var->$level1->$level2 ) && $level2 ){
			return;
		}
		if( ! empty( $var->$level1->$level2 ) && $level2 ){
			return $var->$level1->$level2;
		}
		if( empty( $var->$level1->$level2->$level3 ) && $level3 ){
			return;
		}
		if( ! empty( $var->$level1->$level2->$level3 ) && $level3 ){
			return $var->$level1->$level2->$level3;
		}
		return $var->$level1;
	}
}

if ( ! function_exists( 'wpkeyvalue' ) ){
	function wpkeyvalue( $var, $key, $echo = false, $multiple = false ){
		if( $multiple && is_array( $key ) ){
			$output = '';
			if( isset( $key[0] ) ){
				$output = $var[$key[0]];
			}
			if( isset( $key[1] ) ){				
				if( ! isset( $var[$key[0]] ) ){ return; }
				$output = $var[$key[0]][$key[1]];
			}
			if( isset( $key[2] ) ){				
				if( ! isset( $var[$key[0]] ) ){ return; }
				if( ! isset( $var[$key[0]][$key[1]] ) ){ return; }
				$output = $var[$key[0]][$key[1]][$key[2]];
			}
			if( isset( $key[3] ) ){				
				if( ! isset( $var[$key[0]] ) ){ return; }
				if( ! isset( $var[$key[0]][$key[1]] ) ){ return; }
				if( ! isset( $var[$key[0]][$key[1]][$key[2]] ) ){ return; }
				$output = $var[$key[0]][$key[1]][$key[2]][$key[3]];
			}
			if( $echo ){
				echo $output;
				return $output;
			}else{
				return $output;
			}
		}
		if( ! is_array( $var ) ){
			return;
		}
		if( isset( $var[$key] ) && ! $echo ){
			return $var[$key];
		}
		if( isset( $var[$key] ) && $echo ){
			echo $var[$key];
		}
		return;
	}
}

if ( ! function_exists( 'wpget' ) ){
	function wpget( $key, $echo = false, $sanitize = false ){
		if( isset( $_GET[$key] ) && ! $echo ){
			return wpsanitizeswitch( $_GET[$key], $sanitize );
		}
		if( isset( $_GET[$key] ) && $echo ){
			echo wpsanitizeswitch( $_GET[$key], $sanitize );
		}
		return;
	}
}

if ( ! function_exists( 'wpsession' ) ){
	function wpsession( $key, $echo = false, $sanitize = false ){
		if( isset( $_SESSION[$key] ) && ! $echo ){
			return wpsanitizeswitch( $_SESSION[$key], $sanitize );
		}
		if( isset( $_SESSION[$key] ) && $echo ){
			echo wpsanitizeswitch( $_SESSION[$key], $sanitize );
		}
		return;
	}
}

if ( ! function_exists( 'wprequest' ) ){
	function wprequest( $key, $echo = false, $sanitize = false ){
		if( isset( $_REQUEST[$key] ) && ! $echo ){
			return wpsanitizeswitch( $_REQUEST[$key], $sanitize );
		}
		if( isset( $_REQUEST[$key] ) && $echo ){
			echo wpsanitizeswitch( $_REQUEST[$key], $sanitize );
		}
		return;
	}
}

if ( ! function_exists( 'wpsanitizeswitch' ) ){
	function wpsanitizeswitch( $output, $sanitize = false ){
		switch( $sanitize ){
			case 'email':
				return sanitize_email( $output );
				break;
			case 'file_name':
				return sanitize_file_name( $output );
				break;
			case 'html_class':
				return sanitize_html_class( esc_attr( $output ) );
				break;
			case 'key':
				return sanitize_key( $output );
				break;
			case 'title':
				return sanitize_title( $output );
				break;
			case 'title_for_query':
				return sanitize_title_for_query( $output );
				break;
			case 'title_with_dashes':
				return sanitize_title_with_dashes( $output );
				break;
			case 'user':
				return sanitize_user( $output );
				break;
			case 'textarea_field':
				return sanitize_textarea_field( $output );
				break;
			default:
				return sanitize_text_field( $output );
		}
	}
}

if ( ! function_exists( 'wppost' ) ){
	function wppost( $key, $echo = false, $sanitize = 'text_field' ){
		if( isset( $_POST[$key] ) && ! $echo ){
			return wpsanitizeswitch( $_POST[$key], $sanitize );
		}
		if( isset( $_POST[$key] ) && $echo ){
			echo wpsanitizeswitch( $_POST[$key], $sanitize );
		}
		return;
	}
}

if ( ! function_exists( 'wprequest' ) ){
	function wprequest( $key, $echo = false, $sanitize = 'text_field' ){
		if( isset( $_REQUEST[$key] ) && ! $echo ){
			return wpsanitizeswitch( $_REQUEST[$key], $sanitize );
		}
		if( isset( $_REQUEST[$key] ) && $echo ){
			echo wpsanitizeswitch( $_REQUEST[$key], $sanitize );
		}
		return;
	}
}