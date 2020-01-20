<?php

/*
PHP PDO Extention

Author: @evgeniikucheriavii
Repository: https://github.com/evgeniikucheriavii/PHP-PDO-Extention

This class allows you commit all your queries with one method

*/

class Database
{

	public static function Query($sql, $values = null)
	{
		$result = false;

		$pdo = self::CreatePDO();

		$stmt = $pdo->prepare($sql);
		$r = $stmt->execute($values);

		if($r)
		{
			if(substr($sql, 0, strlen("INSERT")) == "INSERT")
			{
				$result = $pdo->lastInsertId(); //If a new row was inserted, method will return it's ID
			}
			else
			{
				if(substr($sql, 0, strlen("UPDATE")) == "UPDATE" || substr($sql, 0, strlen("DELETE")) == "DELETE")
				{
					$result = true; //If a row was updated successfully, method will return true
				}
				else 
				{
					$array = $stmt->fetchAll(); 

					if($array != null && count($array) > 0)
					{
						if(count($array) == 1)
						{
							$result = $array[0];
						}
						else
						{
							$result = $array;
						}
					}
					else
					{
						$result = true;
					}
				}
			}
		}

		return $result;
	}

	private static function CreatePDO()
	{
		$host = "";
		$db   = "";
		
		$PDOSettings['user'] = "";
		$PDOSettings['pass'] = "";
		$charset = 'utf8';

		$PDOSettings['dsn'] = "mysql:host=$host;dbname=$db;charset=$charset";
		$PDOSettings['opt'] = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];
		
		return new PDO($PDOSettings['dsn'], $PDOSettings['user'], $PDOSettings['pass'], $PDOSettings['opt']);
	}
}

?>
