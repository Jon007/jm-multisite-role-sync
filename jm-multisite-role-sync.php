<?php
/**
 * @package JM_Multisite_Role_Sync
 * @version 1.0
 */
/*
Plugin Name: JM Multisite Role Sync
Plugin URI: https://github.com/Jon007/jm-multisite-role-sync
Description: Synchronise roles between parent and child site
Author: J.Moore
Version: 1.0
Author URI: https://jonmoblog.wordpress.com/
*/
/** 
 * Duplicate capabilities and user_level rows in usermeta table
 *
 * @param int    $user_id   The user ID.
 * @param string $role      The new role.
 * 
 * @link https://developer.wordpress.org/reference/hooks/set_user_role/
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/set_user_role
 * 
 */
function ksu_save_role( $user_id, $role ) {

    static $saving_role = false;
    if ($saving_role){return;}
    $saving_role = true;
    
	$prefix_1 = 'wp_';
	$prefix_2 = 'wp_2_';

    $blogid = get_current_blog_id();
    if ($blogid ==2){
        $prefix_2 = 'wp_';
        $prefix_1 = 'wp_2_';        
    }
	
	$caps = get_user_meta( $user_id, $prefix_1 . 'capabilities', true );
	$level = get_user_meta( $user_id, $prefix_1 . 'user_level', true );
    /* not necessary to merge the roles otherwise it would be impossible to remove...
	$caps2 = get_user_meta( $user_id, $prefix_2 . 'capabilities', true );
	$level2 = get_user_meta( $user_id, $prefix_2 . 'user_level', true );
    $caps = array_merge($caps, $caps2);
    $level = array_merge($level, $level2);
    */
	if ( $caps ){
		update_user_meta( $user_id, $prefix_2 . 'capabilities', $caps );
	}

	if ( $level ){
		update_user_meta( $user_id, $prefix_2 . 'user_level', $level );
	}
    $saving_role = false;
}

add_action( 'set_user_role', 'ksu_save_role', 10, 2 );
add_action( 'add_user_role', 'ksu_save_role', 10, 2 );
add_action( 'remove_user_role', 'ksu_save_role', 10, 2 );
