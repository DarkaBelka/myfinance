<?php
/**
 **	Отрисовка формы для ввода на главной странице
 **	@param	$connection		подключение к БД
 **	@param	$formTitle		заголовок формы, соответсвует проводимой операции
 ** @param	$table			имя таблицы для составления первого выпадающего списка, соответсвует проводимой операции
 ** @param	$columnName		имя столбца из $table для составления выпадающего списка
 ** @param	$nameSelect 	имя поля для первого выпадающего списка
 ** @param	$titleSelect	заголовок первого выпадающего списка
 ** @param	$commentSelect1	комментарий перед первым выпадающим списком
 ** @param	$commentSelect2	комментарий перед вторым выпадающим списком
 **/
function mainForm($connection,$formTitle,$table,$columnName,$nameSelect,$titleSelect,$commentSelect1,$commentSelect2)
{
	echo <<<_END
		<div class='tableAddDel mainForm'>
		<form action="main.php" method="post">
			<p>$formTitle</p>
			<div class='inc_exp'>
				$commentSelect1
_END;
				selectSmth($connection,$columnName,$table,$nameSelect,$titleSelect);
				echo "<br>";

				echo $commentSelect2;
				$name = 'accountName';
				$table = 'accounts';
				$nameSelect = 'accountName';
				$titleSelect = 'Счет';
				selectSmth($connection,$name,$table,$nameSelect,$titleSelect);

			echo "</div><br>";

			echo "Выберите имя пользователя:";
			$name = 'userName';
			$table = 'users';
			$nameSelect = 'userName';
			$titleSelect = 'Имя пользователя';
			selectSmth($connection,$name,$table,$nameSelect,$titleSelect);
			//$query = "SELECT userName FROM users";
			//selectSmth($connection,$query,'userName','Имя пользователя');
			echo "<br>";

	echo <<<_END
			Введите сумму: <input type="text" name="value" placeholder="0.00"><br>
			Введите дату: <input type="date" name="date"><br>
			Введите комментарий: <br>
			<textarea name="comment" cols="30" rows="10" wrap='soft'>Вводите комментарий к операции (не обязательно).</textarea><br>
			<input type="submit" value="Добавить"><br>
		</form>
		</div>
_END;
}
?>