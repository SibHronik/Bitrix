<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use ULRA\HLBObjectList;

Loc::loadMessages(__FILE__);

class closest_number extends CModule
{
    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this -> MODULE_VERSION = $arModuleVersion["VERSION"];
            $this -> MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this -> MODULE_ID = "closest.number";
        $this -> MODULE_NAME = "Поиск чисел";
        $this -> MODULE_DESCRIPTION = "Поиск ближайшего числа в массиве";
        $this -> MODULE_GROUP_RIGHTS = "N";
        $this -> PARTNER_NAME = "Sibhronik";
        $this -> PARTNER_URI = "https://irksea.ru"; // Не работает
    }

    function installFiles()
    {
        CopyDirFiles(
            $_SERVER["DOCUMENT_ROOT"]."/local/modules/closest.number/install/components/sibhronik",
            $_SERVER["DOCUMENT_ROOT"]."/local/components/sibhronik", true, true
        );
        CopyDirFiles(
            $_SERVER["DOCUMENT_ROOT"]."/local/modules/closest.number/sibhronik",
            $_SERVER["DOCUMENT_ROOT"]."/sibhronik", true, true
        );
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFilesEx("/local/components/sibhronik/closest.number");
        DeleteDirFilesEx("/sibhronik");
        return true;
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this -> MODULE_ID);
        $this -> installFiles();
    }

    public function doUninstall()
    {
        $this -> unInstallFiles();
        ModuleManager::unRegisterModule($this -> MODULE_ID);
    }
}