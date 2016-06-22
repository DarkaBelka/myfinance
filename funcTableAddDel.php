<?php
require_once 'funcTableShow.php';

function tableAddDel($connection,$query,$header,$cols,$table)
{
	$result = $connection->query($query);

	if (!$result) echo "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";
}
?>