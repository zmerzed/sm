<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );


add_filter('register','no_register_link');
function no_register_link($url){
    return '';
}

add_action('login_enqueue_scripts', 'strength_mextrix_login_scripts', 10);
function strength_mextrix_login_scripts(){
	wp_enqueue_script( 'strength_mextrix.js', get_stylesheet_directory_uri() . '/js/strength_metrix.js', array( 'jquery' ), 1.0 );
	wp_enqueue_style( 'strength_mextrix_login.css', get_stylesheet_directory_uri() . '/css/strength_mextrix_login.css', array(  ) );
}


/*
*
* Check what is the buddyPress member type
* Redirect them to a specific pages. 
*
*/

function sm_login_redirect( $redirect_to, $request, $user ) {
	//is there a user to check?
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		
		$member_type = bp_get_member_type($user->data->ID);

		//check for admins
		if ( in_array( 'administrator', $user->roles ) ) {
			// redirect them to the default place
			return $redirect_to;

		} else if( $member_type == 'client' ){

			return home_url() . '/client';

		} else if( $member_type == 'trainer' ){

			return home_url() . '/trainer';

		}


	} else {
		return $redirect_to;
	}
}

add_filter( 'login_redirect', 'sm_login_redirect', 10, 3 );


// END ENQUEUE PARENT ACTION
