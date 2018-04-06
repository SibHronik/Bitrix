<?php
use \Bitrix\Main\Localization\Loc;

global $APPLICATION;

if (!check_bitrix_sessid()) {
	return;
}

Loc::loadMessages(__FILE__);

echo CAdminMessage::ShowMessage(Loc::getMessage('USERUPDATE_UNINSTALL_STEP2'));
?>
<form action="<?echo $APPLICATION->GetCurPage();?>">
	<input type="hidden" name="lang" value="<?echo LANGUAGE_ID;?>" />
	<input type="submit" name="" value="<?echo Loc::getMessage('USERUPDATE_GOBACK_STEP2');?>" />
</form>