<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Sibhronik\Quiz\QuizTable;
use Sibhronik\Quiz\QuestionsTable;
use Sibhronik\Quiz\AnswersTable;
use Sibhronik\Quiz\UserAnswersTable;
use Sibhronik\Quiz\UsersAnsweredTable;

Loc::loadMessages(__FILE__);

class sibhronik_quiz extends CModule
{
    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this -> MODULE_VERSION = $arModuleVersion["VERSION"];
            $this -> MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this -> MODULE_ID = "sibhronik.quizes";
        $this -> MODULE_NAME = Loc::getMessage("QUIZ_MODULE_NAME");
        $this -> MODULE_DESCRIPTION = Loc::getMessage("QUIZ_MODULE_DESCRIPTION");
        $this -> MODULE_GROUP_RIGHTS = "N";
        $this -> PARTNER_NAME = Loc::getMessage("QUIZ_MODULE_PARTNER_NAME");
        $this -> PARTNER_URI = "https://irksea.ru";
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this -> MODULE_ID);
        $this -> installDB();
        $this -> installFiles();
    }

    public function doUninstall()
    {
        $this -> uninstallDB();
        $this -> unInstallFiles();
        ModuleManager::unRegisterModule($this -> MODULE_ID);
    }

    public function installFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/sibhronik.quizes/install/components/sibhronik", $_SERVER["DOCUMENT_ROOT"]."/local/components/sibhronik", true, true);
        return true;
    }

    public function unInstallFiles()
    {
        DeleteDirFilesEx("/local/components/sibhronik/");
        return true;
    }

    public function installDB()
    {
        if (Loader::includeModule($this -> MODULE_ID)) {
            QuizTable::getEntity() -> createDbTable();
            QuestionsTable::getEntity() -> createDbTable();
            AnswersTable::getEntity() -> createDbTable();
            UserAnswersTable::getEntity() -> createDbTable();
            UsersAnsweredTable::getEntity() -> createDbTable();
        }
    }

    public function uninstallDB()
    {
        if (Loader::includeModule($this -> MODULE_ID)) {
            if (Application::getConnection() -> isTableExists(Base::getInstance("\Sibhronik\Quiz\QuizTable") -> getDBTableName())) {
                $connection = Application::getInstance() -> getConnection();
                $connection -> dropTable(QuizTable::getTableName());
            }
            if (Application::getConnection() -> isTableExists(Base::getInstance("\Sibhronik\Quiz\QuestionsTable") -> getDBTableName())) {
                $connection = Application::getInstance() -> getConnection();
                $connection -> dropTable(QuestionsTable::getTableName());
            }
            if (Application::getConnection() -> isTableExists(Base::getInstance("\Sibhronik\Quiz\AnswersTable") -> getDBTableName())) {
                $connection = Application::getInstance() -> getConnection();
                $connection -> dropTable(AnswersTable::getTableName());
            }
            if (Application::getConnection() -> isTableExists(Base::getInstance("\Sibhronik\Quiz\UserAnswersTable") -> getDBTableName())) {
                $connection = Application::getInstance() -> getConnection();
                $connection -> dropTable(UserAnswersTable::getTableName());
            }
            if (Application::getConnection() -> isTableExists(Base::getInstance("\Sibhronik\Quiz\UsersAnsweredTable") -> getDBTableName())) {
                $connection = Application::getInstance() -> getConnection();
                $connection -> dropTable(UsersAnsweredTable::getTableName());
            }
        }
    }
}