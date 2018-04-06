<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config as Conf;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class strlog_userupdate extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();
        
        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_ID = 'strlog.userupdate';
        $this->MODULE_NAME = Loc::getMessage('USERUPDATE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('USERUPDATE_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('USERUPDATE_PARTNER_NAME');
        $this->PARTNER_URI = 'https://стройлогистика.рф';
    }

    public function DoInstall()
    {
		global $APPLICATION;
		$this->installEvents();
		$this->installFiles();
		ModuleManager::registerModule($this->MODULE_ID);
		$APPLICATION->IncludeAdminFile(Loc::getMessage('USERUPDATE_STEP'), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/strlog.userupdate/install/step.php");
    }

    public function DoUninstall()
    {
		global $APPLICATION;
		$context = Application::getInstance()->getContext();
		$request = $context->getRequest();
		if ($request["step"] < 2) {
			$APPLICATION->IncludeAdminFile(Loc::GetMessage('USERUPDATE_UNSTEP'), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/strlog.userupdate/install/unstep1.php");
		} elseif ($request["step"] == 2) {
			$this->uninstallEvents();
			$this->uninstallFiles();
			if ($request['savedata'] != 'Y') {

			}
			ModuleManager::unRegisterModule($this->MODULE_ID);
			$APPLICATION->IncludeAdminFile(Loc::GetMessage('USERUPDATE_UNSTEP'), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/strlog.userupdate/install/unstep2.php");
		}
    }

	function GetModuleRightList()
	{
		return array(
			"reference_id" => array("D", "K", "S", "W"),
			"reference" => array(
				"[D] "."Доступ закрыт",
				"[K] "."Настройка личных данных",
				"[S] "."Доступ открыт всем",
				"[W] "."Админский доступ",
			),
		);
	}
}












