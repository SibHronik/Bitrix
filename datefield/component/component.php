<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\UserTable as Users;

//Удаляем пустые поля(Можно удалить этот цикл и делать проверку на наличие значения в template.php)
foreach ($arParams["CALENDAR_TYPE"] as $key => $value) {
	if (!$value || $value == '') {
		unset($arParams["CALENDAR_TYPE"][$key]);
	} else {
		$arParams["CALENDAR_TYPE"][$key] = $value;
	}
}

$arResult["CALENDAR_DATES"] = $arParams["CALENDAR_TYPE"];

$this->IncludeComponentTemplate();