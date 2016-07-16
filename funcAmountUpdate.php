<?php
/** 
 **	Меняет сумму на счету после операций доход/расход/перевод между счетами
 **	@param	$connection	подключение к БД 
 **	@param	$accountID	ID счета, на котором меняется сумма
 **	@param	$value		сумма зачисляемая на счет, с учетом знака
 **/
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