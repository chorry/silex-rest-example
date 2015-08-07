<?php
namespace app\database;

use Silex\Provider\DoctrineServiceProvider;

class DBManager {
	public static function getConnection($app)
	{
		switch ($app['config']['database']['type'])
		{
			case 'mysql':
				return new MysqlDB($app['config']['database']);
			case 'file':
				return new FileDB($app['config']['database']);
			default:
				throw new \Exception(
					sprintf('not supported db type %s', $app['config']['database']['type'])
				);
		}
	}
}