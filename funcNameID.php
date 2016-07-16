<?php
/**
 **	По заданному имени ищет значение ID в соответсвующей таблицу БД
 **	@param	$connection	подключение к БД
 ** @param	$ID			имя столбца, содержащего значения ID в соответсвующей таблице
 ** @param	$table		имя таблицы, содержащей ID и имена
 ** @param	$name		имя столбца, содержащего имена в таблице
 ** @param	$inputName	значение имени, для которого необходимо найти значение ID
 **	@return	значения ID, найденное в таблице $table
 **/
	function nameToID($connection,$ID,$table,$name,$inputName)
	{
		$query = "SELECT " . $ID . " FROM " . $table . " WHERE " . $name . " ='$inputName'";
		$result = $connection->query($query);
		if (!$result) "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	}
/**
 **	По заданному ID ищет значение имени в соответсвующей таблицу БД
 **	@param	$connection	подключение к БД
 ** @param	$name		имя столбца, содержащего имена в соответсвующей таблице
 ** @param	$table		имя таблицы, содержащей ID и имена
 ** @param	$ID			имя столбца, содержащего значения ID в таблице
 ** @param	$row		!!!ЯТП массив, соответсвующий строке из какого-то запроса ПЕРЕДЕЛАТЬ!!!
 **	@param	$i			!!!ЯТП номер элемента массив $row ПЕРЕДЕЛАТЬ!!!
 **	@return	значения ID, найденное в таблице $table
 **/
	function IDtoName($connection,$name,$table,$ID,$row,$i)
	{
		$queryID = "SELECT $name FROM $table WHERE $ID=$row[$i]";
		$resultID = $connection->query($queryID);

		if (!$resultID) echo "Сбой при доступе к базе данных: $queryID<br>" . $connection->error . "<br><br>";
		$rowsID = $resultID->num_rows;
		$nameID = $resultID->fetch_array(MYSQLI_NUM);
		return $nameID[0];
	}
?>