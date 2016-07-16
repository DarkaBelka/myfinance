<?php
/**
 **	Добавляет и удаляет строки таблицы
 **	@param	$connection	подключение к БД
 **	@param	$query		запрос,который удаляет/добавляет запись в таблице
 ** @param	$header		заголовок таблицы
 ** @param	$cols		количество полей таблицы
 ** @param	$table		имя изменяемой таблицы
 **/

function tableAddDel($connection,$query,$header,$cols,$table)
{
	$result = $connection->query($query);

	if (!$result) echo "Сбой при доступе к базе данных: $query<br>" . $connection->error . "<br><br>";
}
?>