<?php
	function nameToID($connection,$ID,$table,$name,$inputName)
	{
		$query = "SELECT " . $ID . " FROM " . $table . " WHERE " . $name . " ='$inputName'";
		$result = $connection->query($query);
		if (!$result) "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	}

	function IDtoName($connection,$name,$table,$ID,$row,$i)
	{
		$queryID = "SELECT $name FROM $table WHERE $ID=$row[$i]";
		$resultID = $connection->query($queryID);

		if (!$resultID) echo "Сбой при доступе к базе данных: $queryID<br>" . $connection->error . "<br><br>";
		$rowsID = $resultID->num_rows;
		$userNameID = $resultID->fetch_array(MYSQLI_NUM);
		$row[$i] = $userNameID[0];

		echo "<td>$row[$i]</td>";
	}
?>