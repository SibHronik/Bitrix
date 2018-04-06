<?php
use \Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) {
	return;
}

echo CAdminMessage::ShowNote(Loc::getMessage("USERUPDATE_MODULE_INSTALLED"));
?>
<form action="<?=$APPLICATION->GetCurPage();?>">
	<input type="hidden" name="lang" value="<? echo LANGUAGE_ID; ?>" />
	<input type="submit" value="<?=Loc::getMessage("USERUPDATE_GOBACK")?>" />
</form>

