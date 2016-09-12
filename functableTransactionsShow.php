<?php
/** Выводит таблицу операций
 **	@param	$connection	подключение к БД
 **	@param	$table		таблица операций
 **	@param	$header		заголовок таблицы
 **	@param	$cols		количество полей в таблице
 **/
function tableTransactionsShow($connection,$table,$header,$cols)
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
		echo "<td>$row[0]</td>";

		$row[1] = IDtoName($connection,'userName','users','userID',$row,1);
		echo "<td>$row[1]</td>";

		if ($row[0] == 1)
		{
			$row[2] = IDtoName($connection,'incomeName','income','incomeID',$row,2);
			echo "<td>$row[2]</td>";
			$row[3] = IDtoName($connection,'accountName','accounts','accountID',$row,3);
			echo "<td>$row[3]</td>";
			echo "<td class='incomeValue'>+$row[4]</td>";
		}
		elseif ($row[0] == -1)
		{
			$row[2] = IDtoName($connection,'accountName','accounts','accountID',$row,2);
			echo "<td>$row[2]</td>";
			$row[3] = IDtoName($connection,'expendName','expenditure','expendID',$row,3);
			echo "<td>$row[3]</td>";
			echo "<td class='expendValue'>-$row[4]</td>";
		}
		elseif ($row[0] == 0)
		{
			$row[2] = IDtoName($connection,'accountName','accounts','accountID',$row,2);
			echo "<td>$row[2]</td>";
			$row[3] = IDtoName($connection,'accountName','accounts','accountID',$row,3);
			echo "<td>$row[3]</td>";
			echo "<td class='Value'>$row[4]</td>";
		}

		for ($i = 5 ; $i < $cols ; $i++)
		{
			echo "<td>$row[$i]</td>";
		}

		echo "</tr>";
	}
	echo "</table>";
}
?>