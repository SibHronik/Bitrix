<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
foreach ($arResult['CALENDAR_DATES'] as $key => $value) {
	echo GetMessage('CALENDAR_DATE_VALUE').$value.'<br />';
}
?>