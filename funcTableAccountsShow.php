<?php
/** Выводит таблицу счетов
 **	@param	$connection	подключение к БД
 **	@param	$table		таблица счетов
 **	@param	$header		заголовок таблицы
 **	@param	$cols		количество полей в таблице
 **/
function tableAccountsShow($connection,$table,$header,$cols)
{
	$query = "SELECT * FROM " . $table;
	$result = $connection->query($query);

	if (!$result) echo "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";

	$rows = $result->num_rows;
	echo "<table> $header";

	for ($j = 0 ; $j < $rows ; ++$j)
	{
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_NUM);

		echo "<tr>";
		for ($i = 0 ; $i < $cols-1 ; $i++)
		{
			echo "<td>$row[$i]</td>";
		}

		$row[$i] = IDtoName($connection,'userName','users','userID',$row,$i);
		echo "<td>$row[$i]</td>";

		echo "</tr>";
	}
	echo "</table>";
}
?>