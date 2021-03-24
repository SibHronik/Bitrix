<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тестовая страница");
?>

<?php if ($_REQUEST["USER_ID"] && !empty($_REQUEST["USER_ID"])) {
    global $APPLICATION;
    $userID = htmlspecialcharsEx(trim($_REQUEST["USER_ID"]));
    $APPLICATION->RestartBuffer();
} else {
    $userID = "";
}
?>
<div id="news-statistics-ajax-wrapper">
<?php $APPLICATION->IncludeComponent(
    "local:company.news.statistics",
    ".default",
    [
        "COMPONENT_TEMPLATE" => ".default",
        "USERS" => $userID,
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_SHADOW" => "N",
        "AJAX_OPTION_STYLE" => "N",
    ],
    false
); ?>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>