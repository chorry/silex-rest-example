<?php
namespace app\database;

use app\interfaces\Databaseable;
use PDO;

class MysqlDB implements Databaseable {
	public function __construct($config)
	{
		$this->_connection = new PDO(
			$this->getDSN($config['dbname'], $config['host']),
			$config['user'],
			$config['password']
		);
	}

	protected function getDSN($db, $host)
	{
		return sprintf('mysql:dbname=%s;host=%s', $db, $host);
	}

	public function save($object)
	{
		$q = $this->_connection->prepare(
			'INSERT INTO ' . $object->getTableName() . ' VALUES (:id,:title,:body,:created,:modified)
			 on DUPLICATE KEY UPDATE title=VALUES(title), body=VALUES(body), created=VALUES(created), modified=VALUES(modified)'
		);

		$q->bindValue(":id",$object->id);
		$q->bindValue(":title",$object->title);
		$q->bindValue(":body",$object->body);
		$q->bindValue(":created",$object->created);
		$q->bindValue(":modified",$object->modified);

		if ($q->execute())
		{
			$object->id = $this->_connection->lastInsertId();
			return true;
		}

		return false;
	}

	public function delete($id)
	{
		$q = $this->_connection->prepare('DELETE FROM news where id = :id');
		$q->bindParam(":id",$id);
		return $q->execute() ;
	}

	public function find($id = null)
	{
		if ($id)
		{
			$query = sprintf( 'SELECT * FROM news where ID = %d', $id );
			return $this->_connection->query($query, PDO::FETCH_ASSOC)->fetchObject();
		}
		else
		{
			$query = 'SELECT * FROM news';
			return $this->_connection->query($query, PDO::FETCH_ASSOC)->fetchAll();
		}

	}
}