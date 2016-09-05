<?php 
/**
 *  Plugin Name: Use Admin Email as From Address
 *  Plugin URI:  https://burobjorn.nl
 *  Description: Use the admin email address set in Settings->General as from email address
 *  Author:      Bj&ouml;rn Wijers <burobjorn@burobjorn.nl>
 *  Version:     1
 *  Author URI:  https://burobjorn.nl
 *  License:     GPL2 or later
 **/

/**
 * NOTE: 
 * This plugin fixes a bug in WordPress 4.6 (and possible version 4.5.3):
 * 
 * If a From email address is *NOT* set *AND* the $_SERVER[ 'SERVER_NAME' ] is empty
 * WordPress will generate an invalid email address 'wordpress@'
 * 
 * by explicitly setting the From email address we prevent this bug from
 * happening. 
 * 
 * See also issue #25239:
 * https://core.trac.wordpress.org/ticket/25239  
 **/

if( ! class_exists( 'AdminEmailAsFromAddress' ) ) {
  class AdminEmailAsFromAddress {

    function __construct() {
      add_filter( 'wp_mail_from',      array( $this, 'set_from_email' ) ); 
      add_filter( 'wp_mail_from_name', array( $this, 'set_from_name'  ) );    
    } 


    /** 
     * Called by 'wp_mail_from' filter
     * Allows setting the From email address 
     * uses the admin_email option by design, so we're not adding 
     * more settings. If the email address needs to be changed 
     * you need to change the 'admin_email' in Settings->General
     *
     * 
     * @param string current email address used as from address
     * @return string new email address used for from address
     *
     **/
    function set_from_email( $email ) {
      $admin_email = get_bloginfo( 'admin_email' );  
      $mail = empty( $admin_email ) ? $email : $admin_email; 
      return $mail;
    }


    /**
     * Called by filter 'wp_mail_from_name'  
     * Allows setting the name used in the From email header
     * defaults to WordPress
     * 
     * @param string current name
     * @return string new name, defaults to WordPress
     *
     * @TODO allow to set this in General->Settings under 'admin email' 
     *   
     **/ 
    function set_from_name( $name ){
      return 'WordPress';
    }
  }
  $admin_email_as_from_address = new AdminEmailAsFromAddress; 
} else {
  error_log( 'Class AdminEmailAsFromAddress already exists. Plugin activation failed' );
}
?>
