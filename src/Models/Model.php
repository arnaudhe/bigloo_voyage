<?php

namespace Models;

use Models\ModelTools;

class Model
{
	private   $dbConnection;

	private   $qbLoad;

	private   $tableName;

	private   $id;

	private   $attributes;

	private   $existingInDB;

	private   $empty;

	function __construct($db, $table)
	{
		$this->dbConnection = $db;
		$this->tableName = $table;
		ModelTools::checkSchema($db, $table);
		$this->loadSchema();
		$this->existingInDB = false;
		$this->empty = true;
	}

	function loadSchema()
	{
		foreach(ModelTools::getColumnsList($this->dbConnection, $this->tableName) as $field)
		{
			$this->attributes[$field] = "";
		}
	}

	function load($id)
	{
		$this->id = $id;

		$attributesDB = ModelTools::select($this->dbConnection, $this->tableName, '*', array(ModelTools::$PM_FIELD_NAME => $id));

		if(count($attributesDB) == 1)
		{
			$this->empty = false;
			$this->attributes = $attributesDB[0];
			$this->existingInDB = true;
		}
		else if(count($attributesDB) > 1)
		{
			throw new \Exception("Doublon found in database (" . ModelTools::$PM_FIELD_NAME . " = " . $id . ")");
		}
		else
		{
			$this->empty = false;
			$this->existingInDB = false;
		}

		return $this->existingInDB;
	}

	function setAttributes($aAttributes)
	{
		foreach ($aAttributes as $key => $value)
		{
			$this->empty = false;
			if(array_key_exists($key, $this->attributes))
			{
				$this->attributes[$key] = $value;
			}
		}
		$this->save();
	}

	function setAttribute($key, $value)
	{
		if(!array_key_exists($key, $this->attributes))
		{
			return;
		}

		$this->empty = false;
		$this->attributes[$key] = $value;
		$this->save();
	}

	function getAttribute($key)
	{
		if(!array_key_exists($key, $this->attributes))
		{
			return "";
		}
		else
		{
			return $this->attributes[$key];
		}
	}

	function getAttributes()
	{
		return $this->attributes;
	}

	function getId()
	{
		return $this->id;
	}
	
	function save()
	{
		if($this->empty)
		{
			throw new \Exception("Trying to save an empty record in database");
		}
		
		if($this->existingInDB)
		{
			try
			{
				$this->dbConnection->update($this->tableName, $this->attributes, array(ModelTools::$PM_FIELD_NAME => $this->id));
			}
			catch (Doctrine\DBAL\DBALException $e)
			{
			    throw new \Exception("DATABASE UPDATE ERROR - " . $e->getMessage());
			}
		}
		else
		{
			try
			{
				$this->dbConnection->insert($this->tableName, $this->attributes);
				$this->id = $this->dbConnection->lastInsertId();
				$this->attributes[ModelTools::$PM_FIELD_NAME] = $this->id;
			}
			catch (Doctrine\DBAL\DBALException $e)
			{
			    throw new \Exception("DATABASE INSERT ERROR - " . $e->getMessage());
			}
		}
		
		$rtn = $this->existingInDB;
		$this->existingInDB = true;

		return $rtn;
	}

	function toString()
	{
		$result = '';
		foreach ($this->attributes as $key => $value)
		{
			$result .= $key . " : " . $value . " ; ";
		}

		return $result;
	}
}