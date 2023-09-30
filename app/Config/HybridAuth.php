<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
* Author: https://roytuts.com
*/

class HybridAuth extends BaseConfig {

    public static $callback = site_url();
    public static $providers = array(

		'Twitter' => array(
			'callback' => site_url('hauth/window/twitter'),
			'enabled' => TRUE,
			'keys' => array(
				'key' => '...',
				'secret' => '...'
			),
			'include_email' => TRUE,
		),

		'Google'   => ['enabled' => true, 'keys' => ['id'  => '...', 'secret' => '...']],
		'Facebook' => ['enabled' => true, 'keys' => ['id'  => '...', 'secret' => '...']],
	);

    /**
     * Optional: Debug Mode
     *
     * The debug mode is set to false by default, however you can rise its level to either 'info', 'debug' or 'error'.
     *
     * debug_mode: false|info|debug|error
     * debug_file: Path to file writeable by the web server. Required if only 'debug_mode' is not false.
     */
    public static $debugMode = ENVIRONMENT === 'development';
    public static $debugFile = APPPATH . 'logs/hybridauth.log';
}