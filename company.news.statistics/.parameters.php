<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$cacheTime = 86400;
$cacheId = "CompanyNewsStatisticsID";
$cacheDir = "CompanyNewsStatistics";
$cache = Bitrix\Main\Data\Cache::createInstance();
if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
    $users = $cache->getVars();
    $users = $users["USERS"];
}

if (!isset($users) || count($users) < 1 || empty($users)) {
    if ($cache->startDataCache()) {
        $queryUser = \Bitrix\Main\UserTable::getList([
            "filter" => ["ACTIVE" => "Y"],
            "select" => ["ID", "NAME", "LAST_NAME", "LOGIN"],
        ]);
        $users = [];
        while ($user = $queryUser -> fetch()) {
            $users[$user["ID"]] = $user["LAST_NAME"] . " " . $user["NAME"] . " [" . $user["LOGIN"] . "]";
        }
        asort($users);
        $cache->endDataCache(["USERS" => $users]);
    }
}

try{
    $arComponentParameters = [
        "GROUPS" => [
            "SETTINGS" => [
                "NAME" => GetMessage("SETTINGS")
            ],
        ],
        "PARAMETERS" => [
            "USERS" => [
                "PARENT" => "SETTINGS",
                "NAME" => GetMessage("USER_LIST"),
                "TYPE" => "LIST",
                "VALUES" => $users,
                "ADDITIONAL_VALUES" => "N",
                "SIZE" => 10,
                "MULTIPLE"  =>  "N",
                "DEFAULT" => "",
                "CACHE_TYPE" => "A",
            ],
        ],
        "AJAX_MODE" => [],
    ];
}
catch (\Exception $e)
{
    global $USER;
    if ($USER->IsAdmin()) {
        ShowError($e->getMessage());
    }
}