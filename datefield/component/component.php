<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\UserTable as Users;

$arResult["CALENDAR_DATES"] = $arParams["CALENDAR_TYPE"];

$this->IncludeComponentTemplate();