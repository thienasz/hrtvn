<?php
/**
 * Options Framework
 *
 * @package   Options Framework
 * @author    Devin Price <devin@wptheming.com>
 * @license   GPL-2.0+
 * @link      http://wptheming.com
 * @copyright 2010-2014 WP Theming
 *
 * @wordpress-plugin
 * Plugin Name: Options Framework
 * Plugin URI:  http://wptheming.com
 * Description: A framework for building theme options.
 * Version:     1.8.4
 * Author:      Devin Price
 * Author URI:  http://wptheming.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: optionsframework
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if(!function_exists('look_optionsframework_init'))
{
    function look_optionsframework_init() {

        //  If user can't edit theme options, exit
        if ( !current_user_can( 'edit_theme_options' ) )
            return;

        // Loads the required Options Framework classes.
        require THEME_DIR . '/modules/options/includes/class-options-init.php';
        require THEME_DIR . '/modules/options/includes/class-options-framework.php';
        require THEME_DIR . '/modules/options/includes/class-options-framework-admin.php';
        require THEME_DIR . '/modules/options/includes/class-options-interface.php';
        require THEME_DIR . '/modules/options/includes/class-options-media-uploader.php';
        require THEME_DIR . '/modules/options/includes/class-options-sanitization.php';

        // Instantiate the main plugin class.
        $options_framework = new Look_Options_Framework;
        $options_framework->init();

        // Instantiate the options page.
        $options_framework_admin = new Look_Options_Framework_Admin;
        $options_framework_admin->init();

        // Instantiate the media uploader class
        $options_framework_media_uploader = new Look_Options_Framework_Media_Uploader;
        $options_framework_media_uploader->init();

    }
}


add_action( 'init', 'look_optionsframework_init', 20 );

/**
 * Helper function to return the theme option value.
 * If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * Not in a class to support backwards compatibility in themes.
 */

if ( ! function_exists( 'look_get_option' ) ) :

    function look_get_option( $name, $default = false ) {
        $config = get_option( 'optionsframework' );
        $optionsfile = THEME_DIR.'/modules/options/options.php';
        require_once $optionsfile;

        $options = look_optionsframework_options();
        if(!$default)
        {
            foreach($options as $op)
            {
                if(isset($op['id']) && isset($op['std']) && $op['id'] == $name)
                {
                    $default = $op['std'];
                }
            }
        }
        if ( ! isset( $config['id'] ) ) {
            return $default;
        }

        $options = get_option( $config['id'] );

        if ( isset( $options[$name] ) ) {
            return $options[$name];
        }

        return $default;
    }

endif;
