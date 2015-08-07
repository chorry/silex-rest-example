<?php
namespace app\interfaces;
interface Databaseable //i suck at giving names, i know
{
	public function save($object);
	public function delete($id);
	public function find($id);

}