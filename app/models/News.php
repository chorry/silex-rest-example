<?php

namespace app\models;

class News
{
	public $id;
	public $title;
	public $body;
	public $created;
	public $modified;

	public function __construct($data = false)
	{
		if ($data)
			$this->setAttributes($data, true);
	}

	static function createRandomNews()
	{
		$x = new News();
		$x->id = microtime(true);
		$x->title = "Title for ".$x->id;
		$x->body = "Body for ".$x->id;
		$x->created = $x->modified = time();
		return $x;
	}

	public function isValid()
	{
		return ($this->title != '' && $this->body != '') ? true : false;
	}

	public function setAttributes($data, $allowUnsafe = false)
	{
		if ($this->id == '')
			$this->created = date("Y-m-d H:i:s", time() );
		foreach ($data as $k=>$v)
		{
			if ( property_exists($this, $k) && !in_array($k, $this->getProtectedAttrs($allowUnsafe)))
			{
				$this->$k = $v;
				$this->modified = date("Y-m-d H:i:s", time() );
			}
		}
		return $this;
	}

	public function getAttributes()
	{
		return get_object_vars($this);
	}

	public function getTableName()
	{
		return 'news';
	}

	public function getProtectedAttrs($skipCheck = false)
	{
		return ($skipCheck) ? [] : ['id','created','modified'];
	}
}