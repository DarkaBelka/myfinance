<?php
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

	require_once 'loginDB.php';
	require_once 'funcTableAddDel.php';

	$connection = new mysqli($dbHostname,$dbUsername,$dbPassword,$dbDatabase);

	if ($connection -> connect_error) die($connection -> connect_error);

	echo <<<_END
		<a href="main.php"> Главная</a>
		<a href="usersAddDel.php"> Добавить/удалить пользователя</a>
		<a href="accountsAddDel.php"> Добавить/удалить счет</a>
		<a href="incomeAddDel.php"> Добавить/удалить источник дохода</a>
		<a href="expenditureAddDel.php"> Добавить/удалить статью расхода</a>

		<div class='tableAddDel'>
		<form action="main.php" method="post">
			Выберите тип операции: <br>
_END;
		echo "<div class='inc_exp'>";
			echo "Доходы";
			$query = "SELECT incomeName FROM income";
			selectSmth($connection,$query,'income');
		echo "</div>";

		echo "<div class='inc_exp'>";
			echo "Расходы";
			$query = "SELECT expendName FROM expenditure";
			selectSmth($connection,$query,'expenditure');
		echo "</div><br>";


	echo "Выберите имя пользователя:";
	$query = "SELECT userName FROM users";
	selectSmth($connection,$query,'userName');
	echo "<br>";

	echo "Выберите название счета:";
	$query = "SELECT accountName FROM accounts";
	selectSmth($connection,$query,'accountName');
	echo "<br>";

	echo <<<_END
			Введите сумму: <input type="text" name="value" placeholder="0.00"><br>
			Ввведите дату: <input type="date" name="date"><br>
			Введите комментарий: <br>
			<textarea name="comment" cols="30" rows="10" wrap='soft'>Вводите комментарий к операции (не обязательно).</textarea><br>
			<input type="submit" value="Добавить"><br>
		</form>
		</div>
_END;

	$header = " <tr>
					<th>Тип операции</th>
					<th>Пользователь</th>
					<th>Счет</th>
					<th>Статья дохода/расхода</th>
					<th>Сумма</th>
					<th>Дата</th>
					<th>ID операции</th>
					<th>Комментарий</th>
				</tr>";
	$cols = 8;
	$table = 'transactions';

	if (
		(isset($_POST['income']) || isset($_POST['expenditure'])) &&
		isset($_POST['userName']) &&
		isset($_POST['accountName']) &&
		isset($_POST['value']) &&
		isset($_POST['date'])
		)
	{
		$userName 			= $_POST['userName'];
		$accountName 		= $_POST['accountName'];
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
				" VALUES ('1','$userID','$accountID','$incomeID','$value','$date',NULL,'$comment')";
		}
		if (isset($_POST['expenditure']))
		{
			$inc_exp = $_POST['expenditure'];
			$expendID = nameToID($connection,'expendID','expenditure','expendName',$inc_exp);
			$query = "INSERT INTO " . $table .
				" VALUES ('0','$userID','$accountID','$expendID','$value','$date',NULL,'$comment')";
		}
		tableAddDel($connection,$query,$header,$cols,$table);
		tableTransactionsShow($connection,$table,$header,$cols);
	}
	else
	{
		tableTransactionsShow($connection,$table,$header,$cols);
	}

	function selectSmth($connection,$query,$name)
	{
		$result = $connection->query($query);

		if (!$result) echo "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";

		$rows = $result->num_rows;
		echo "<select size='1' name=" .
			$name . ">";
		echo "<option disabled selected>-------</option>";
		for ($j = 0 ; $j < $rows ; ++$j)
		{
			$result->data_seek($j);
			$row = $result->fetch_array(MYSQLI_NUM);
			echo "<option value='$row[0]'>$row[0]</option>";
		}
		echo "</select>";
	}

	function nameToID($connection,$ID,$table,$name,$inputName)
	{
		$query = "SELECT " . $ID . " FROM " . $table . " WHERE " . $name . " ='$inputName'";
		$result = $connection->query($query);
		if (!$result) "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	}

	function IDtoName($connection,$name,$table,$ID,$row,$i)
	{
		$queryID = "SELECT $name FROM $table WHERE $ID=$row[$i]";
		$resultID = $connection->query($queryID);

		if (!$resultID) echo "Сбой при доступе к базе данных: $queryID<br>" . $connection->error . "<br><br>";
		$rowsID = $resultID->num_rows;
		$userNameID = $resultID->fetch_array(MYSQLI_NUM);
		$row[$i] = $userNameID[0];

		echo "<td>$row[$i]</td>";
	}

	function tableTransactionsShow($connection,$table,$header,$cols)
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
			echo "<td>$row[0]</td>";

			IDtoName($connection,'userName','users','userID',$row,1);

			IDtoName($connection,'accountName','accounts','accountID',$row,2);

			//IDtoName($connection,'accountName','accounts','accountID',2);

			for ($i = 4 ; $i < $cols ; $i++)
			{
				echo "<td>$row[$i]</td>";
			}

			echo "</tr>";
		}
		echo "</table>";
	}

	echo "</body></html>";

	$connection->close();
?>