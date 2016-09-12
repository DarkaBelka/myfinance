<!DOCTYPE html>
<!--
 ** Главная страница.
 ** Позволяет добавлять финансовые операции: доходы, расходы, передод средств между счетами.
 -->

<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="main.css" media="all">
	<title>Финансовый калькулятор</title>
</head>
<body>
	<a href="main.php"> Главная</a>
	<a href="usersAddDel.php"> Добавить/удалить пользователя</a>
	<a href="accountsAddDel.php"> Добавить/удалить счет</a>
	<a href="incomeAddDel.php"> Добавить/удалить источник дохода</a>
	<a href="expenditureAddDel.php"> Добавить/удалить статью расхода</a>
	<br>Выберите тип операции: <br>

<?php
	require_once 'funcFile.php';

	$connection = new mysqli($dbHostname,$dbUsername,$dbPassword,$dbDatabase);
	if ($connection -> connect_error) die($connection -> connect_error);

	$formTitle = 'Доходы';
	$table = 'income';
	$columnName = 'incomeName';
	$nameSelect = 'income';
	$titleSelect = 'Источник дохода';
	$commentSelect1 = 'Выберите источник дохода';
	$commentSelect2 = 'Выберите название счета';
	mainForm($connection,$formTitle,$table,$columnName,$nameSelect,$titleSelect,$commentSelect1,$commentSelect2);

	$formTitle = 'Расходы';
	$table = 'expenditure';
	$columnName = 'expendName';
	$nameSelect = 'expenditure';
	$titleSelect = 'Статья расхода';
	$commentSelect1 = 'Выберите статью расхода';
	$commentSelect2 = 'Выберите название счета';
	mainForm($connection,$formTitle,$table,$columnName,$nameSelect,$titleSelect,$commentSelect1,$commentSelect2);

	$formTitle = 'Перевод между счетами';
	$table = 'accounts';
	$columnName = 'accountName';
	$nameSelect = 'accountFrom';
	$titleSelect = 'Счет';
	$commentSelect1 = 'Выберите счет списания';
	$commentSelect2 = 'Выберите счет внесения';
	mainForm($connection,$formTitle,$table,$columnName,$nameSelect,$titleSelect,$commentSelect1,$commentSelect2);

	$header = " <tr>
					<th>Тип операции</th>
					<th>Пользователь</th>
					<th>Источник средств</th>
					<th>Цель</th>
					<th>Сумма</th>
					<th>Дата</th>
					<th>ID операции</th>
					<th>Комментарий</th>
					<th>Время внесения</th>
				</tr>";
	$cols = 9;
	$table = 'transactions';

	if (
		(isset($_POST['income']) || isset($_POST['expenditure']) || isset($_POST['accountFrom'])) &&
		isset($_POST['userName']) &&
		isset($_POST['accountName']) &&
		isset($_POST['value']) &&
		isset($_POST['date'])
		)
	{
		$accountName 		= $_POST['accountName'];
		$userName 			= $_POST['userName'];
		$value 				= $_POST['value'];
		$date 				= $_POST['date'];
		$comment 			= $_POST['comment'];

		//Функции для связи имени и ID, позволяет связывать между собой таблицы БД
		$ID = 'userID';
		$tableForNameToID = 'users';
		$columnName = 'userName';
		$userID 	= nameToID($connection,$ID,$tableForNameToID,$columnName,$userName);
		$ID = 'accountID';
		$tableForNameToID = 'accounts';
		$columnName = 'accountName';
		$accountID 	= nameToID($connection,$ID,$tableForNameToID,$columnName,$accountName);

		//Выбор варианта операции
		if ( isset($_POST['income']) ) //доход
		{
			$inc_exp = $_POST['income'];
			$ID = 'incomeID';
			$tableForNameToID = 'income';
			$columnName = 'incomeName';
			$incomeID = nameToID($connection,$ID,$tableForNameToID,$columnName,$inc_exp);
			$query = "INSERT INTO $table
				VALUES ('1','$userID','$incomeID','$accountID','$value','$date',NULL,'$comment',now())";
			amountUpdate($connection,$accountID,$value); //Изменение суммы на счету после оепрации
		}
		elseif ( isset($_POST['expenditure']) ) //Расход
		{
			$inc_exp = $_POST['expenditure'];
			$ID = 'expendID';
			$tableForNameToID = 'expenditure';
			$columnName = 'expendName';
			$expendID = nameToID($connection,$ID,$tableForNameToID,$columnName,$inc_exp);
			$query = "INSERT INTO $table
				VALUES ('-1','$userID','$accountID','$expendID','$value','$date',NULL,'$comment',now())";
			amountUpdate($connection,$accountID,-$value);
		}
		elseif ( isset($_POST['accountFrom']) ) //Перевод
		{
			$inc_exp = $_POST['accountFrom'];
			$ID = 'accountID';
			$tableForNameToID = 'accounts';
			$columnName = 'accountName';
			$accountFromID = nameToID($connection,$ID,$tableForNameToID,$columnName,$inc_exp);
			$query = "INSERT INTO $table
				VALUES ('0','$userID','$accountFromID','$accountID','$value','$date',NULL,'$comment',now())";
			amountUpdate($connection,$accountID,$value);
			amountUpdate($connection,$accountFromID,-$value);
		}

		//Отрисовка таблицы
		tableTransactionsShow($connection,$table,$header,$cols);
	}
	else
	{
		tableTransactionsShow($connection,$table,$header,$cols);
	}

	$connection->close();
?>
</body></html>