<?php
use Bitrix\Main\Localization\Loc;

global $APPLICATION;

if(!check_bitrix_sessid())
{
return;
}

Loc::loadMessages(__FILE__);
?>

<form action="<?=$APPLICATION->GetCurPage();?>">
	<?=bitrix_sessid_post();?>
	<input type="hidden" name="lang" value="<?echo LANGUAGE_ID;?>" />
	<input type="hidden" name="id" value="strlog.userupdate" />
	<input type="hidden" name="uninstall" value="Y" />
	<input type="hidden" name="step" value="2" />
	<?echo CAdminMessage::ShowMessage(Loc::getMessage('USERUPDATE_UNINSTALL_MESS'));?>
	<p><?echo Loc::getMessage('USERUPDATE_DATA_SAVE');?></p>
	<p>
		<input type="checkbox" name="savedata" id="savedata" value="Y" checked />
		<label for="savedata"><?echo Loc::getMessage('USERUPDATE_SAVEDATA');?></label>
	</p>
	<input type="submit" name="" value="<?echo Loc::getMessage('USERUPDATE_UNINSTALL_FINAL');?>" />
</form>