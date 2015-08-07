<?php
namespace app\interfaces;
interface OrmInterface
{
	static function find( $params );

	public function update( $object );
	public function delete( $params );
	public function create( $data );
	public function save();
}