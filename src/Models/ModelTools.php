<?php

namespace Models;

class ModelTools
{
	public static $PM_FIELD_NAME = "id";

	static function checkSchema($dbConnection, $tableName)
	{
		$sm = $dbConnection->getSchemaManager();
		$result = false;
		$tables = $sm->listTables();

		$columns = ModelTools::getColumnsList($dbConnection, $tableName);

		if(!in_array(ModelTools::$PM_FIELD_NAME, $columns))
		{
			throw new \Exception("DATABASE SCHEMA ERROR - Primary Key field " . ModelTools::$PM_FIELD_NAME . " not found");
		}
	}

	static function getColumnsList($dbConnection, $tableName)
	{
		$columns = array();
		$sm = $dbConnection->getSchemaManager();
		$result = false;
		$tables = $sm->listTables();

		foreach ($tables as $table)
		{
		    if($table->getName() == $tableName)
		    {
		    	foreach ($table->getColumns() as $column)
		    	{
			        array_push($columns, $column->getName());
			    }
			    return $columns;
		    }
		}
		throw new \Exception("DATABASE SCHEMA ERROR - Table " . $tableName . " not found");
	}

	static function select($dbConnection, $table, $fields, array $identifier)
	{
		if(is_array($fields))
		{
			$fieldsString = implode(', ', $fields);
		}
		else
		{
			$fieldsString = $fields;
		}

		$criteria = array();
		$params = array_values($identifier);
        foreach (array_keys($identifier) as $columnName)
        {
            $criteria[] = $columnName . ' = ?';
        }
		$filter = implode(' AND ', $criteria);

		$qbLoad = $dbConnection->createQueryBuilder();
		$qbLoad->select($fields)
			   ->from($table, 'ALIAS');
		if(count($identifier) > 0)
		{
			$qbLoad->where($filter);
		}
		$qbLoad->setParameters($params);

		try
		{
			$result = $qbLoad->execute()->fetchAll();
		}
		catch (Doctrine\DBAL\DBALException $e)
		{
		    throw new \Exception("DATABASE SELECT ERROR - " . $e->getMessage());
		}

		return $result;
	}
}