<?php
function amountUpdate($connection,$accountID,$value)
{
	$query = "SELECT amount FROM accounts WHERE accountID = '$accountID'";
	$result = $connection->query($query);

	if (!$result) echo "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";

	$row = $result->fetch_array(MYSQLI_NUM);
	$amount = $row[0] + $value;

	$query = "UPDATE accounts SET amount = '$amount' WHERE accountID = '$accountID'";
	$result = $connection->query($query);

	if (!$result) echo "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";
}
?>