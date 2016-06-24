<?php
function selectSmth($connection,$query,$nameSelect,$titleSelect)
{
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