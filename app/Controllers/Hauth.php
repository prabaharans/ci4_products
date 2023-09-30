<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\HybridAuthLib;

/**
 * Hauth Controller Class
 */
class Hauth extends BaseController
{
    use ResponseTrait;

    protected $helpers = [];
    protected $hybridauth;

	/**
	 * {@inheritdoc}
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
        $this->helpers = ['url'];
        $this->hybridauth = new HybridAuthLib();
        parent::initController($request, $response, $logger);
	}

	/**
	 * {@inheritdoc}
	 */
	public function index()
	{
		// Build a list of enabled providers.
		$providers = array();
		foreach ($this->hybridauth->HA->getProviders() as $name) {
			$uri = 'hauth/window/' . strtolower($name);
			$providers[] = anchor($uri, $name);
		}

		view('hauth/login_widget', array(
			'providers' => $providers,
		));
	}

	/**
	 * Try to authenticate the user with a given provider
	 *
	 * @param string $provider Define provider to login
	 */
	public function window($provider)
	{
		try {
			$adapter = $this->hybridauth->HA->authenticate($provider);
			$profile = $adapter->getUserProfile();

			view('hauth/done', array(
				'profile' => $profile,
			));
		} catch (Exception $e) {
			show_error($e->getMessage());
		}
	}
}