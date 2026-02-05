<?php
/**
 * Flexx marketing client plugin for WordPress
 *
 * @link              https://flexxmarketing.nl/
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Flexxmarketing Client Plugin
 * Description:       Klanten plugin voor Flexx marketing, met custom functionaliteit en Visual Composer elementen.
 * Version:           2.0.0
 * Author:            Duncan Heffron | <a href="https://flexxmarketing.nl/" target="_blank">Flexxmarketing</a>
 * Text Domain:       flexx-client-plugin
 * Requires at least: 6.0.0
 * Tested up to:      6.7.1
 * Requires PHP:      8.1
 */

// No direct access
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'FLEXX_CP_VERSION', '2.0.0' );
define( 'FLEXX_CP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); // plugin_dir_path returns the trailing slash (!)
define( 'FLEXX_CP_PLUGIN_FILE', __FILE__ );
define( 'FLEXX_CP_ASSETS_URL', plugin_dir_url(__FILE__) . 'assets' );
define( 'FLEXX_CP_IMG_URL', FLEXX_CP_ASSETS_URL . '/images/' );
define( 'FLEXX_CP_CSS_URL', FLEXX_CP_ASSETS_URL . '/css/' );
define( 'FLEXX_CP_TEXT_DOMAIN', 'flexx-client-plugin' );


/**
 * Function: Class Autoloader for Flexx Client Plugin
 *
 * @since 1.0.0
 * @param string $class_name The fully qualified class name.
 */

    function flexx_client_plugin_autoloader( $class_name ) {

        // Ensure only classes from this plugin are loaded
        if ( false === strpos( $class_name, 'Flexx_Client_Plugin' ) ) {
            return;
        }

        // Split class name into parts
        $parts = explode( '\\', $class_name );

        // Initialize namespace and filename variables
        $namespace = '';
        $file_name = '';

        // Loop through class name parts
        for ( $i = count( $parts ) - 1; $i > 0; $i-- ) {
            $current = strtolower( $parts[ $i ] );
            $current = str_ireplace( '_', '-', $current );

            if ( count( $parts ) - 1 === $i ) {
                // Handle Visual Composer (VC) elements differently
                if ( strpos( $current, 'flexx-vc-' ) !== false ) {
                    $file_name = substr( $current, 9 ) . '.php';
                } else {
                    $file_name = "class-$current.php";
                }
            } else {
                $namespace = DIRECTORY_SEPARATOR . $current . $namespace;
            }
        }

        // Set up class path
        $class_path = trailingslashit( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . $namespace );
        $class_path .= $file_name;

        // If file exists, include it, otherwise display an error
        if ( file_exists( $class_path ) ) {
            include_once( $class_path );
        } else {
            wp_die(
                esc_html( "Error: The file attempting to be loaded at $class_path does not exist." )
            );
        }
    }

    // Register the autoloader
    spl_autoload_register( 'flexx_client_plugin_autoloader' );

/**
 * Function: Instantiating the Flexx Client Plugin
 */

    function flexx_client_plugin_init() {
        // Ensure the autoloader is registered before creating an instance
        if ( class_exists( 'Flexx_Client_Plugin\Plugin_Base' ) ) {
            new Flexx_Client_Plugin\Plugin_Base();
        } else {
            wp_die( esc_html( 'Error: Plugin_Base class not found. Check the autoloader setup.' ) );
        }

        // Include settings file
        require_once FLEXX_CP_PLUGIN_DIR . '/settings.php';
    }

    // Create instance of the plugin
    flexx_client_plugin_init();

