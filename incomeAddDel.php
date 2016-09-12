<!DOCTYPE html>
<html>
<!--
 ** Позволяет добавлять/удалять источники дохода
 -->
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="main.css" media="all">	
	<title>Добавить/удалить пользователя</title>
</head>
<body>
	<a href="main.php"> Главная</a>
	<a href="usersAddDel.php"> Добавить/удалить пользователя</a>
	<a href="accountsAddDel.php"> Добавить/удалить счет</a>
	<a href="incomeAddDel.php"> Добавить/удалить источник дохода</a>
	<a href="expenditureAddDel.php"> Добавить/удалить статью расхода</a>

	<div class='tableAddDel'>
	<form action="incomeAddDel.php" method="post">
		Доходы.<br>
		Добавить источники доходов:<br>
		Введите источник доходов: <input type="text" name="incomeNameAdd" placeholder="Источик дохода"><br>
		<input type="submit" value="Добавить"><br>
	</form>
	<form action="incomeAddDel.php" method="post">
		Удалить источники доходов:<br>
		Введите источник доходов: <input type="text" name="incomeNameDel" placeholder="Источник дохода"><br>
		<input type="submit" value="Удалить"><br>
	</form>
	</div>

<?php
	require_once 'funcFile.php';

	$connection = new mysqli($dbHostname,$dbUsername,$dbPassword,$dbDatabase);
	if ($connection -> connect_error) die($connection -> connect_error);

	$header = "<tr>
				<th>Источник дохода</th>
				<th>ID иточника</th>
			   </tr>";
	$cols = 2;
	$table = 'income';

	if ( isset($_POST['incomeNameAdd']) )
	{
		$name = $_POST['incomeNameAdd'];
		$query = "INSERT INTO $table  VALUES ('$name',NULL)";
		tableAddDel($connection,$query,$header,$cols,$table);
		tableShow($connection,$table,$header,$cols);
	}
	elseif ( isset($_POST['incomeNameDel']) )
	{
		$name = $_POST['incomeNameDel'];
		$query = "DELETE FROM $table WHERE incomeName='$name'";
		tableAddDel($connection,$query,$header,$cols,$table);
		tableShow($connection,$table,$header,$cols);
	}
	else
	{
		tableShow($connection,$table,$header,$cols);
	}
?>
</body></html>