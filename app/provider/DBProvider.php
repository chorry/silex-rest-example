<?php
namespace app\provider;
use app\database\DBManager;
use Silex\Application;
use Silex\ServiceProviderInterface;

class DBProvider implements ServiceProviderInterface {
	/**
	 * Registers services on the given app.
	 *
	 * This method should only be used to configure services and parameters.
	 * It should not get services.
	 */
	public function register( Application $app )
	{
		DBManager::getConnection($app);//->register($app);
	}

	/**
	 * Bootstraps the application.
	 *
	 * This method is called after all services are registered
	 * and should be used for "dynamic" configuration (whenever
	 * a service must be requested).
	 */
	public function boot( Application $app )
	{
		// TODO: Implement boot() method.
	}

}