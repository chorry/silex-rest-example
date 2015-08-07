<?php
namespace app\models;


class EntityManager
{
	public $bridge;

	public function __construct($bridge)
	{
		$this->bridge = $bridge;
	}

	public function __call($method, $args)
	{
		return $this->bridge->$method($args[0]);
	}

}