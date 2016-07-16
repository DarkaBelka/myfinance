<?php
/**
 ** Позволяет добавлять/удалять пользователей
 **/
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

		//форма добавить/удалить
		<div class='tableAddDel'>
		<form action="usersAddDel.php" method="post">
			Пользователи.<br>
			Добавить пользователя:<br>
			Введите имя: <input type="text" name="userNameAdd" placeholder="Имя пользователя"><br>
			<input type="submit" value="Добавить"><br>
		</form>
		<form action="usersAddDel.php" method="post">
			Удалить пользователя:<br>
			Введите имя: <input type="text" name="userNameDel" placeholder="Имя пользователя"><br>
			<input type="submit" value="Удалить"><br>
		</form>
		</div>
_END;
	$header = "<tr>
				<th>Имя пользоватедя</th>
				<th>ID пользоватедя</th>
			   </tr>";
	$cols = 2;
	$table = 'users';

	//добавить
	if (isset($_POST['userNameAdd']))
	{
		$name = $_POST['userNameAdd'];
		$query = "INSERT INTO $table VALUES ('$name',NULL)";
		tableAddDel($connection,$query,$header,$cols,$table); //функция вставки/удаления строки
		tableShow($connection,$table,$header,$cols); //отрисовка таблицы
	}
	//удалить
	elseif ( isset($_POST['userNameDel']) )
	{
		$name = $_POST['userNameDel'];
		$query = "DELETE FROM $table WHERE userName='$name'";
		tableAddDel($connection,$query,$header,$cols,$table); //функция вставки/удаления строки
		tableShow($connection,$table,$header,$cols); //отрисовка таблицы
	}
	else
	{
		tableShow($connection,$table,$header,$cols); //отрисовка таблицы
	}

	echo "</body></html>";
?>