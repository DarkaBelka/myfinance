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
	require_once 'funcNameID.php';	

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
			selectSmth($connection,$query,'income','Источник дохода');
		echo "</div>";

		echo "<div class='inc_exp'>";
			echo "Расходы";
			$query = "SELECT expendName FROM expenditure";
			selectSmth($connection,$query,'expenditure','Статья расхода');
		echo "</div>";

		echo "<div class='inc_exp'>";
			echo "Перевод";
			$query = "SELECT accountName FROM accounts";
			selectSmth($connection,$query,'accountIn','Счет зачисления');
		echo "</div><br>";

	echo "Выберите имя пользователя:";
	$query = "SELECT userName FROM users";
	selectSmth($connection,$query,'userName','Имя пользователя');
	echo "<br>";

	echo "Выберите название счета:";
	$query = "SELECT accountName FROM accounts";
	selectSmth($connection,$query,'accountName','Счет');
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
					<th>123</th>
					<th>Пользователь</th>
					<th>Счет</th>
					<th>Статья дохода/расхода/счет зачисления</th>
					<th>Сумма</th>
					<th>Дата</th>
					<th>ID операции</th>
					<th>Комментарий</th>
					<th>Время внесения</th>
				</tr>";
	$cols = 9;
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
		//$now = NOW();

		if (isset($_POST['income']))
		{
			$inc_exp = $_POST['income'];
			$incomeID = nameToID($connection,'incomeID','income','incomeName',$inc_exp);
			$query = "INSERT INTO " . $table .
				" VALUES ('1','$userID','$accountID','$incomeID','$value','$date',NULL,'$comment',now())";
		}
		elseif (isset($_POST['expenditure']))
		{
			$inc_exp = $_POST['expenditure'];
			$expendID = nameToID($connection,'expendID','expenditure','expendName',$inc_exp);
			$query = "INSERT INTO " . $table .
				" VALUES ('-1','$userID','$accountID','$expendID','$value','$date',NULL,'$comment',now())";
			$value = - $value;
		}
		elseif (isset($_POST['accountIn']))
		{
			$inc_exp = $_POST['accountIn'];
			$expendID = nameToID($connection,'expendID','expenditure','expendName',$inc_exp);
			$query = "INSERT INTO " . $table .
				" VALUES ('0','$userID','$accountID','$expendID','$value','$date',NULL,'$comment',now())";
		}

		tableAddDel($connection,$query,$header,$cols,$table);

		$query = "SELECT amount FROM accounts WHERE accountID = '$accountID'";
		$result = $connection->query($query);

		if (!$result) echo "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";

		$row = $result->fetch_array(MYSQLI_NUM);
		$amount = $row[0] + $value;

		$query = "UPDATE accounts SET amount = '$amount' WHERE accountID = '$accountID'";
		$result = $connection->query($query);

		if (!$result) echo "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";

		tableTransactionsShow($connection,$table,$header,$cols);
	}
	else
	{
		tableTransactionsShow($connection,$table,$header,$cols);
	}

	function selectSmth($connection,$query,$name,$title)
	{
		$result = $connection->query($query);

		if (!$result) echo "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";

		$rows = $result->num_rows;
		echo "<select size='1' name=" .
			$name . ">";
		echo "<option disabled selected>$title</option>";
		for ($j = 0 ; $j < $rows ; ++$j)
		{
			$result->data_seek($j);
			$row = $result->fetch_array(MYSQLI_NUM);
			echo "<option value='$row[0]'>$row[0]</option>";
		}
		echo "</select>";
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

			if ($row[0] == 1)
			{
				IDtoName($connection,'incomeName','income','incomeID',$row,3);
				echo "<td class='incomeValue'>+$row[4]</td>";
			}
			elseif ($row[0] == -1)
			{
				IDtoName($connection,'expendName','expenditure','expendID',$row,3);
				echo "<td class='expendValue'>-$row[4]</td>";
			}
			elseif ($row[0] == 0)
			{
				IDtoName($connection,'expendName','expenditure','expendID',$row,3);
				$row[4] = $row[4];
				echo "<td class='Value'>$row[4]</td>";
			}

			for ($i = 5 ; $i < $cols ; $i++)
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