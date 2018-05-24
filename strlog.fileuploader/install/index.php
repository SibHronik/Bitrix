<?php

use Bitrix\Main\Config;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

global $APPLICATION;

class strlog_fileuploader extends CModule
{

	public function __construct()
	{
		$arModuleVersion = array();
		include __DIR__ . '/version.php';
		if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
		$this->MODULE_ID = 'strlog.fileuploader';
        $this->MODULE_NAME = Loc::getMessage('FILEUPLOAD_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('FILEUPLOAD_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('FILEUPLOAD_PARTNER_NAME');
        $this->PARTNER_URI = 'https://стройлогистика.рф';
	}

	function installFiles()
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/strlog.fileuploader/install/components/sibhronik", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/sibhronik", true, true);
		return true;
	}

	function UnInstallFiles()
	{
		DeleteDirFilesEx("/bitrix/components/sibhronik/fileuploader");
		return true;
	}

	public function DoInstall()
    {
		global $APPLICATION;
		$this->installEvents();
		$this->installFiles();
		ModuleManager::registerModule($this->MODULE_ID);
		$APPLICATION->IncludeAdminFile(Loc::getMessage('FILEUPLOAD_STEP'), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/strlog.fileuploader/install/step.php");
    }

    public function DoUninstall()
    {
		global $APPLICATION;
		$context = Application::getInstance()->getContext();
		$request = $context->getRequest();
		$this->uninstallEvents();
		$this->UnInstallFiles();
		ModuleManager::unRegisterModule($this->MODULE_ID);
    }

}