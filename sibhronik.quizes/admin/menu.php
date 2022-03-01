<?php

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$aMenu[] = [
    "parent_menu" => "global_menu_services",
    "sort" => 1,
    "text" => Loc::getMessage("MODULE_MENU_TITLE"),
    "url" => "sibhronik_quiz/quiz_index.php",
    "items_id" => "menu_quiz",
    /*"items" => [
        [
            "text" => Loc::getMessage("MODULE_PAGE_QUIZ"),
            "url" => "quiz.php",
            "more_url" => [],
            "title" => Loc::getMessage("MODULE_PAGE_QUIZ")
        ]
    ]*/
];

return $aMenu;