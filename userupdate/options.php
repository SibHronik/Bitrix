<?php
use \Bitrix\Main\DB\Exception;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Loader as Load;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application as App;
use \Strlog\Userupdate\Classes\Merger;
use \Strlog\Userupdate\Classes\UserData;
use \Strlog\Userupdate\Classes\HLFields;
use \Strlog\Userupdate\Classes\UserFields;
use \Strlog\Userupdate\Classes\CheckUsers;

$module_id = 'strlog.userupdate';

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
Loc::loadMessages(__FILE__);

Load::includeModule($module_id);

$request = App::getInstance()->getContext()->getRequest();

if (!empty($request['HL_ID']) && $request['HL_ID'] != '') {
	Option::set("strlog.userupdate", "HL_ID", $request["HL_ID"], false);
	Option::set("strlog.userupdate", "Edit_User_Fields", $request["Edit_User_Fields"], false);
	Option::set("strlog.userupdate", "Edit_HL_Fields", $request["Edit_HL_Fields"], false);
	$userEditFields = UserFields::getUserFields();//Получаем пользователские поля
	$HLFieldsKeys = HLFields::getHLFields(Option::get("strlog.userupdate", "HL_ID"));//Получаем названия ключей для передачи в select
	$countHLArrays = CheckUsers::CheckCount($request['HL_ID']);//Получаем количество массивов в Highload блоке
}

$aTabs = array(
	array(
		'DIV' => 'edit1',
		'TAB' => 'Обновление пользователей',
		'OPTIONS' => array(
			array('HL_ID', 'ID highload блока в котором хранятся выгруженные пользователи', '', array('text', 5)),
			array('Edit_User_Fields', 'По какому полю сравниваем?', '', array('selectbox', $userEditFields)),
			array('Edit_HL_Fields', 'С каким полем сравниваем?', '', array('selectbox', $HLFieldsKeys)),
		),
		'TITLE' => 'Обновление и добавление пользователей из highload блока',
	),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
?>

<?php $tabControl->Begin();?>

<form method="post" action="<?=$APPLICATION->GetCurPage();?>?lang=ru&mid=strlog.userupdate" name="strlog_userupdate_settings">
	<?php 
	$tabControl->BeginNextTab();
	foreach($aTabs as $aTab) {
		if ($aTab['OPTIONS']) {
			__AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
		}
	}
	$tabControl->Buttons();
	?>
	<input type="hidden" name="lang" value="<?echo LANGUAGE_ID;?>" />
	<input type="hidden" name="id" value="strlog.userupdate" />
	<input type="submit" name="check_users" value="Проверить наличие новых пользователей" />
	<?php if (!empty($request['HL_ID']) && $countHLArrays >= 1 || $request['HL_ID'] != "" && $countHLArrays >= 1) {
		echo '<input type="submit" name="merger_users" value="Объединить пользователей" />';
	}
	?>
	<input type="submit" name="Update" value="Сбросить" />
	<?=bitrix_sessid_post();?>
</form>
<?php $tabControl->End();?>

<div class="adm-detail-block">
	<div class="adm-detail-content-wrap">
		<div class="adm-detail-content" style="padding: 12px;">
			<div class="adm-detail-title">Результат</div>
			<div class="adm-detail-content-item-block"> 
				<?php
				if ($request['check_users']) {
					if (!empty($request['HL_ID']) || $request['HL_ID'] != "") {
						if ($countHLArrays >= 1) {
							Merger::merge($userEditFields, $request['Edit_User_Fields'], $request['HL_ID'], $request['Edit_HL_Fields']);
						} else {
							echo '<span style="font-size: 16px;color: #cc0000;">Новых пользователей нет</span>';
						}
					} else {
						echo '<span style="font-size: 16px;color: #cc0000;">Введите ID Highload блока</span>';
					}
				}
				if ($request['merger_users']) {
					Merger::merge($userEditFields, $request['Edit_User_Fields'], $request['HL_ID'], $request['Edit_HL_Fields']);
				}
				if ($request['Update']) {
					echo 'UPDATE';
				}
				?>
			</div>
		</div>
	</div>
</div>






















