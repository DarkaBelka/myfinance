<?php
/**
 ** Фаил для подключения функций
 ** Собраны все функции в проекте
 ** Алфовитный порядок
 **/
	require_once 'loginDB.php'; //данные для доступа к БД

	require_once 'funcAmountUpdate.php'; //изменине суммы на счету после проведенной оперции
	require_once 'funcMainForm.php'; //отрисовка форм на главной странице
	require_once 'funcNameID.php'; //2 функции: - возвращает имя по ID; - возвращает ID по имени;
	require_once 'funcSelectSmth.php'; //формирует html-структуру select из данных столбца какой-либо таблицы
	require_once 'funcTableAccountsShow.php'; //отрисовка таблицы счетов
	//require_once 'funcTableAddDel.php'; //добавление/удаление строк в любую таблицу
	require_once 'funcTableShow.php'; //отрисовка таблиц из 2х столбцов: имя+ID
	require_once 'funcTableTransactionsShow.php'; //отрисовка таблицы оперций
?>