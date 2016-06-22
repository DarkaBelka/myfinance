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
		<form action="expenditureAddDel.php" method="post">
			Расходы.<br>
			Добавить статьи расходов:<br>
			Введите статью расходов: <input type="text" name="expendNameAdd" placeholder="Статья расходов"><br>
			<input type="submit" value="Добавить"><br>
		</form>
		<form action="expenditureAddDel.php" method="post">
			Удалить статьи расходов:<br>
			Введите статью расходов: <input type="text" name="expendNameDel" placeholder="Статья расходов"><br>
			<input type="submit" value="Удалить"><br>
		</form>
		</div>
_END;
	$header = "<tr>
				<th>Статья расхода</th>
				<th>ID статьи</th>
			   </tr>";
	$cols = 2;
	$table = 'expenditure';

	if (isset($_POST['expendNameAdd']))
	{
		$name = $_POST['expendNameAdd'];
		$query = "INSERT INTO " . $table . " VALUES ('$name',NULL)";
		tableAddDel($connection,$query,$header,$cols,$table);
		tableShow($connection,$table,$header,$cols);
	}
	elseif (isset($_POST['expendNameDel']))
	{
		$name = $_POST['expendNameDel'];
		$query = "DELETE FROM " . $table . " WHERE expendName='$name'";
		tableAddDel($connection,$query,$header,$cols,$table);
		tableShow($connection,$table,$header,$cols);
	}
	else
	{
		tableShow($connection,$table,$header,$cols);
	}

	echo "</body></html>";
?>