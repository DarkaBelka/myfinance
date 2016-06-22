﻿<?php
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

	require_once 'loginDB.php';
	require_once 'funcTableAddDel.php';
	require_once 'funcTableShow.php';

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

		$query = "SELECT userID FROM users WHERE userName='$userName'";
		$result = $connection->query($query);
		if (!$result) "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";
		$row = $result->fetch_array(MYSQLI_NUM);
		$userID = $row[0];

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

	function tableAccountsShow($connection,$table,$header,$cols)
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
			for ($i = 0 ; $i < $cols-1 ; $i++)
			{
				echo "<td>$row[$i]</td>";
			}

			$queryID = "SELECT userName FROM users WHERE userID=$row[$i]";
			$resultID = $connection->query($queryID);

			if (!$resultID) echo "Сбой при доступе к базе данных: $queryID<br>" . $connection->error . "<br><br>";
			$rowsID = $resultID->num_rows;
			$userNameID = $resultID->fetch_array(MYSQLI_NUM);
			$row[$i] = $userNameID[0];

			echo "<td>$row[$i]</td>";
			echo "</tr>";
		}
		echo "</table>";
	}

	echo "</body></html>";
?>