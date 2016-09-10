<?php

namespace Models;

class ModelFinder
{
	private $dbConnection;

	function __construct($db)
	{
		$this->dbConnection = $db;
	}

	function findModels($table, array $identifier)
	{
		$attributesDB = ModelTools::select($this->dbConnection, $table, ModelTools::$PM_FIELD_NAME, $identifier);

		$result = array();

		foreach ($attributesDB as $record)
		{
			array_push($result, $record[ModelTools::$PM_FIELD_NAME]);
		}
		
		return $result;
	}

	function findAllModels($table)
	{
		$attributesDB = ModelTools::select($this->dbConnection, $table, ModelTools::$PM_FIELD_NAME, array());

		$result = array();

		foreach ($attributesDB as $record)
		{
			array_push($result, $record[ModelTools::$PM_FIELD_NAME]);
		}
		
		return $result;
	}
}