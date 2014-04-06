<?php
/**
 * WHMCSe Framework
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    1.0
 * @link       https://smyl.es
 */

if (!defined("WHMCS")) die("This file cannot be accessed directly");

class WHMCSe {

	protected static $instance = null;

	/**
	 * __construct
	 */
	function __construct(){
		// define("ADMINAREA", true);
	}

	/**
	 * Singleton Instance
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Log data to WHMCS Module Log
	 * @param  string $description Description of what is being logged
	 * @param  mixed $request     Data to be logged to request section
	 * @param  mixed $response    Data to be logged to response section
	 */
    public static function log($description, $request, $response) {
        logModuleCall('whmcse', $description, $request, $response);
    }

    /**
     * Log debug data to WHMCS Module Log
     * @param  mixed $debugdata  data to be logged in the request section
     * @param  mixed $debugdata2 data to be logged in the response section
     */
    public static function debug($debugdata, $debugdata2) {
        logModuleCall('whmcse', 'debug', $debugdata, $debugdata2);
    }

    /**
     * Get the full URL for WHMCS Installation
     * @param  boolean $SSL Return SSL URL if exists by default
     * @return string       Full URL to WHMCS Installation
     */
    public static function get_url($SSL = true) {
        global $CONFIG;
        $SystemURL = $CONFIG['SystemURL'];
        $SystemSSLURL = $CONFIG['SystemSSLURL'];

        if ( !$SSL || !$SystemSSLURL && $SystemURL ) return $SystemURL;

        if ( $SSL && $SystemSSLURL ) return $SystemSSLURL;

    }

    /**
     * Check for custom admin path
     * @return String Returns custom admin path, or admin by default
     */
    private function get_custom_admin_path() {
        $customadminpath = $GLOBALS['customadminpath'];
        if (!$customadminpath) $customadminpath = "admin";
        return $customadminpath;
    }

    /**
     * Get Admin URL for WHMCS Installation
     * @param boolean $SSL Specify to return SSL URL or not, SSL by default
     * @return String Full URL to WHMCS Installation, SSL is used by default
     */
    public static function get_admin_url($SSL = true) {
        return self::get_url($SSL) . "/" . self::get_custom_admin_path();
    }

    /**
     * Get addon module URL
     * @param  string $module Module name, should be exactly as named in addon module folder
     * @return string         Full URL path to addon module
     */
    public static function get_module_url($module) {
    	return self::get_url() . '/modules/addons/' . $module;
    }

//    Functions below still need to be tested, currently NON functional
    public static function prep_url($url = null, $validate = true, $input_type = null, $input_var = null){

        if($input_type && $input_var){
            $input = "INPUT_" . ucwords($input_type);
            $prepped_url = filter_input($input, $input_var, FILTER_SANITIZE_URL);
        } else {
            $prepped_url = filter_var($url, FILTER_SANITIZE_URL);
        }

        if(!$validate){
            return $prepped_url;
        } else {
            if(filter_var($prepped_url, FILTER_VALIDATE_URL)){
                return $prepped_url;
            } else {
                return false;
            }
        }
    }


    public static function prep_input($input_var, $filter_type = 'STRING', $input_type = 'POST', $validate = TRUE){
        $input = "INPUT_" . strtoupper($input_type);
        $type = 'FILTER_SANITIZE_' . strtoupper($filter_type);
        $prepped_input = filter_input($input, $input_var, FILTER_SANITIZE_URL);

        if(!$validate){
            return $prepped_input;
        } else {
            $type = 'FILTER_VALIDATE_' . strtoupper($filter_type);
            if(filter_var($prepped_input, $type)){
                return $prepped_input;
            } else {
                return false;
            }
        }
    }
}
?>