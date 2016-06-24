<?php
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

		IDtoName($connection,'userName','users','userID',$row,1);
		IDtoName($connection,'accountName','accounts','accountID',$row,2);

		if ($row[0] == 1)
		{
			IDtoName($connection,'incomeName','income','incomeID',$row,3);
			echo "<td class='incomeValue'>+$row[4]</td>";
		}
		elseif ($row[0] == -1)
		{
			IDtoName($connection,'expendName','expenditure','expendID',$row,3);
			echo "<td class='expendValue'>-$row[4]</td>";
		}
		elseif ($row[0] == 0)
		{
			IDtoName($connection,'expendName','expenditure','expendID',$row,3);
			$row[4] = $row[4];
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