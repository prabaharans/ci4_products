<?php
namespace App\Libraries;
use Hybridauth\Exception\Exception;
use Hybridauth\Hybridauth;

/**
 * Hybridauth Class
 */
class HybridAuthLib
{

	/**
	 * Reference to the Hybrid_Auth object
	 *
	 * @var Hybridauth\Hybridauth
	 */
	public $HA;

	/**
	 * Reference to CodeIgniter instance
	 *
	 * @var CI_Controller
	 */
	protected $CI;

	/**
	 * Class constructor
	 *
	 * @param array $config
	 */
	public function __construct($config = array())
	{
        $config = new \Config\HybridAuth();

		// $this->CI = &get_instance();

		// Load the HA config.
		if (!$config) {
			log_message('error', 'Hybridauth config does not exist.');

			return;
		}

		// Get HA config.
		// $config = $this->CI->config->item('hybridauth');
		$arrConfig = [
			'callback' => $config::callback,
			'providers' => $config::providers,
			'debug_mode' => $config::debugMode,
			'debug_fFile' => $config::debugFile
		];
		try {
			// Initialize Hybrid_Auth.
			$this->HA = new Hybridauth($arrConfig);

			log_message('info', 'Hybridauth Class is initialized.');
		} catch (Exception $e) {
			show_error($e->getMessage());
		}
	}
}