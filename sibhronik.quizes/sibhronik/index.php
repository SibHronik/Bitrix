<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Опросы");
?>

<div id="quiz-ajax">
    <?php $APPLICATION->IncludeComponent(
        "sibhronik:sibhronik.quizes",
        ".default",
        [
            "COMPONENT_TEMPLATE" => ".default",
            "AJAX_MODE" => "Y",
            "AJAX_OPTION_HISTORY" => "Y",
            "AJAX_OPTION_JUMP" => "Y",
            "AJAX_OPTION_SHADOW" => "Y",
            "AJAX_OPTION_STYLE" => "Y",
        ],
        false
    );?>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>