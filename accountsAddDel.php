<?php
	echo <<<_END
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="UTF-8">
			<link rel="stylesheet" type="text/css" href="main.css" media="all">
			<title>Добавить/удалить пользователя</title>
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

		<div class='tableAddDel'>
		<form action='accountsAddDel.php' method='post'>
			Счета.
			Добавить счет:<br>
			Введите название счета: <input type='text' name='accountNameAdd' placeholder='Название счета'><br>
			Выберите валюту счета:
			<select size='1' name='currency'>
				<option selected='selected' value='RUR'>RUR</option>
				<option value='USD'>USD</option>
				<option value='EUR'>EUR</option>
			</select><br>
			Введите стартовую сумму на счете: <input type='text' name='amount' placeholder='0.00'><br>
			Введите имя владельца счета: <input type='text' name='userName' placeholder='Имя пользоватедя'><br>
			<input type='submit' value='Добавить'><br>
		</form>
		<form action='accountsAddDel.php' method='post'>
			Удалить счет:<br>
			Введите название счета: <input type="text" name="accountNameDel" placeholder="Название счета"><br>
			<input type="submit" value="Удалить"><br>
		</form>
		</div>
_END;
	$header = " <tr>
					<th>Название счета</th>
					<th>ID счета</th>
					<th>Валюта счета</th>
					<th>Сумма на счету</th>
					<th>Всладелец счета</th>
				</tr>";
	$cols = 5;
	$table = 'accounts';

	if (isset($_POST['accountNameAdd']) &&
		isset($_POST['currency']) &&
		isset($_POST['amount']) &&
		isset($_POST['userName'])
		)
	{
		$accountName = $_POST['accountNameAdd'];
		$currency = $_POST['currency'];
		$amount = $_POST['amount'];
		$userName = $_POST['userName'];

		$userID = nameToID($connection,'userID','users','userName',$userName);

		$query = "INSERT INTO " . $table . " VALUES ('$accountName',NULL,'$currency','$amount','$userID')";
		tableAddDel($connection,$query,$header,$cols,$table);
		tableShow($connection,$table,$header,$cols);
	}
	elseif (isset($_POST['accountNameDel']))
	{
		$name = $_POST['accountNameDel'];
		$query = "DELETE FROM " . $table . " WHERE accountName='$name'";
		tableAddDel($connection,$query,$header,$cols,$table);
		tableAccountsShow($connection,$table,$header,$cols);
	}
	else
	{
		tableAccountsShow($connection,$table,$header,$cols);
	}

	echo "</body></html>";
?>