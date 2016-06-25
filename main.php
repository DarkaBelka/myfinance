﻿<?php
	echo <<<_END
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="UTF-8">
			<link rel="stylesheet" type="text/css" href="main.css" media="all">
			<title>Финансовый калькулятор</title>
		</head>
		<body>
_END;

	require_once 'funcFile.php';	

	$connection = new mysqli($dbHostname,$dbUsername,$dbPassword,$dbDatabase);

	if ($connection -> connect_error) die($connection -> connect_error);

	echo <<<_END
		<a href="main.php"> Главная</a>
		<a href="usersAddDel.php"> Добавить/удалить пользователя</a>
		<a href="accountsAddDel.php"> Добавить/удалить счет</a>
		<a href="incomeAddDel.php"> Добавить/удалить источник дохода</a>
		<a href="expenditureAddDel.php"> Добавить/удалить статью расхода</a>
		Выберите тип операции: <br>
_END;

	$formTitle = 'Доходы';
	$table = 'income';
	$columnName = 'incomeName';
	$nameSelect = 'income';
	$titleSelect = 'Источник дахода';
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

	if ((isset($_POST['income']) || isset($_POST['expenditure']) || isset($_POST['accountFrom'])) &&
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

		$userID 	= nameToID($connection,'userID','users','userName',$userName);
		$accountID 	= nameToID($connection,'accountID','accounts','accountName',$accountName);

		if (isset($_POST['income']))
		{
			$inc_exp = $_POST['income'];
			$incomeID = nameToID($connection,'incomeID','income','incomeName',$inc_exp);
			$query = "INSERT INTO " . $table .
				" VALUES ('1','$userID','$incomeID','$accountID','$value','$date',NULL,'$comment',now())";
			amountUpdate($connection,$accountID,$value);
		}
		elseif (isset($_POST['expenditure']))
		{
			$inc_exp = $_POST['expenditure'];
			$expendID = nameToID($connection,'expendID','expenditure','expendName',$inc_exp);
			$query = "INSERT INTO " . $table .
				" VALUES ('-1','$userID','$accountID','$expendID','$value','$date',NULL,'$comment',now())";
			//$value = - $value;
			amountUpdate($connection,$accountID,-$value);
		}
		elseif (isset($_POST['accountFrom']))
		{
			$inc_exp = $_POST['accountFrom'];
			$accountFromID = nameToID($connection,'accountID','accounts','accountName',$inc_exp);
			$query = "INSERT INTO " . $table .
				" VALUES ('0','$userID','$accountFromID','$accountID','$value','$date',NULL,'$comment',now())";
			amountUpdate($connection,$accountID,$value);
			//$value = - $value;
			amountUpdate($connection,$accountFromID,-$value);
		}

		tableAddDel($connection,$query,$header,$cols,$table);
		tableTransactionsShow($connection,$table,$header,$cols);
	}
	else
	{
		tableTransactionsShow($connection,$table,$header,$cols);
	}

	echo "</body></html>";

	$connection->close();
?>