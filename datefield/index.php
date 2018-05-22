<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Date field");
?>

<?php 
$APPLICATION->IncludeComponent(
	"sibhronik:datefield", 
	".default", 
	array(

	),
	false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>