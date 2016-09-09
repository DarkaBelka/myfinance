<?php
/**
 **	Формирует выпадающий список из данных с одного из полей заданной таблицы
 **	@param	$connection		подключение к БД
 **	@param	$name			имя столбца, из записей которого составляется список
 ** @param	$table			имя таблицы, в которой содержится столбец
 **	@param	$nameSelect		имя поля выпадающего списка
 **	@param	$titleSelect	заголовок выпадающего спискаа
 **/
function selectSmth($connection,$name,$table,$nameSelect,$titleSelect)
{
	$query = "SELECT $name FROM $table";
	$result = $connection->query($query);

	if (!$result) echo "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";

	$rows = $result->num_rows;
	echo "<select size='1' name=" .
		$nameSelect . ">";
	echo "<option disabled selected>$titleSelect</option>";
	for ($j = 0 ; $j < $rows ; ++$j)
	{
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_NUM);
		echo "<option value='$row[0]'>$row[0]</option>";
	}
	echo "</select>";
}
?>