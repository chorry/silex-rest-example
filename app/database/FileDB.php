<?php
namespace app\database;

use app\interfaces\Databaseable;

class FileDB implements Databaseable
{
	public $path;
	public $maxId = null;

	public function __construct($params)
	{
		$this->path = $params['path'];
	}

	public function getNextId()
	{
		if ( !$this->maxId )
		{
			$this->maxId = 0;
			$iter        = new \DirectoryIterator( $this->path );
			while ( $iter->valid() )
			{
				if ( $iter->current()->isFile() && !$iter->current()->isDot() )
					if ( $iter->current()->getBasename() > $this->maxId )
						$this->maxId = $iter->current()->getBasename();
				$iter->next();
			}
		}

		return $this->maxId + 1;
	}

	private function getPath( $id )
	{
		if ( $id == '' )
			throw new \Exception ( 'No id for saving' );

		return $this->path . DIRECTORY_SEPARATOR . $id;
	}

	private function loadFile($fileName)
	{
		return unserialize(
			trim( file_get_contents( $this->getPath($fileName) ) )
		);
	}


	public function save( $object )
	{
		if ($object->id == '')
			$object->id = $this->getNextId();

		return file_put_contents(
			$this->getPath( $object->id ),
			serialize( $object )
		);
	}

	public function delete( $id )
	{
		return unlink( $this->getPath( $id ) );
	}

	public function find( $id = false )
	{
		if ( $id )
		{
			if ( file_exists( $this->getPath( $id ) ) )
				return unserialize( trim( file_get_contents( $this->getPath( $id ) ) ) );

			return false;
		}
		return $this->findAll();
	}

	protected function findAll()
	{
		$result = [];
		$iter = new \DirectoryIterator( $this->path );
		while ( $iter->valid() )
		{
			if ( $iter->current()->isFile() && !$iter->current()->isDot() )
				$result[] = $this->loadFile( $iter->current()->getFilename() );
			$iter->next();
		}
		return $result;
	}
}