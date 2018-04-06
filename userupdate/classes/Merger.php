<?php
namespace Strlog\Userupdate\Classes;

use \Bitrix\Highloadblock as HL;
use \Bitrix\Main\UserTable;
\Bitrix\Main\Loader::includeModule('highloadblock');

class Merger
{
	public static function merge($args, $selectUserKey, $highloadID, $selectHLKey)
	{
		if (!$args) {
			echo '<span style="font-size: 16px;color: #cc0000;">Выберите поля для показа у существующих пользователей</span>';
		} else {
			$arrSystemUsers = [];
			$arrUsers = \Bitrix\Main\UserTable::getList(array(
				'select' => $args,
				'filter' => array('*'),
			));
			while ($arrUserData = $arrUsers->fetch()) {
				$arrSystemUsers[$arrUserData[$selectUserKey]] = $arrUserData;
			}
			echo 'Количество выбранных в базе пользователей: '.count($arrSystemUsers).'<br />';
		}

		$HLResultArr = [];
		$HLBlock = HL\HighloadBlockTable::getById($highloadID)->fetch();
		$HLObject = HL\HighloadBlockTable::compileEntity($HLBlock);
		$HLObjectClass = $HLObject->getDataClass();
		$HLResultArray = $HLObjectClass::getList(array(
			'select' => array('*'),
		));
		while ($HLResult = $HLResultArray->fetch()) {
			$HLResultArr[$HLResult[$selectHLKey]] = $HLResult;
		}
		echo 'Количество выбранных в HL блоке пользователей: '.count($HLResultArr).'<br />';

		foreach ($arrSystemUsers as $sysUserKey => $sysUserValue) {
			if (array_key_exists($sysUserKey, $HLResultArr)) {
				//Здесь подойдет только для моего проекта
				$userID = $arrSystemUsers[$sysUserKey]['ID'];
				$HLID = $HLResultArr[$sysUserKey]['ID'];
				$HLName = $HLResultArr[$sysUserKey]['UF_NAME'];
				$HLSecondName = $HLResultArr[$sysUserKey]['UF_SECOND_NAME'];
				$HLLastName = $HLResultArr[$sysUserKey]['UF_LAST_NAME'];
				$HLEmail = $HLResultArr[$sysUserKey]['UF_EMAIL'];
				$HLPhone = $HLResultArr[$sysUserKey]['UF_PHONE'];
				echo "$HLID - $HLName - $HLSecondName - $HLLastName = $HLEmail - $HLPhone<br />";
				if ($userID != '1' || $userID != 1) {
					$user = new \CUser;
					$userUpdateFields = array(
						"NAME" => $HLName,
						"SECOND_NAME" => $HLSecondName,
						"LAST_NAME" => $HLLastName,
						"LOGIN" => $HLEmail,
						"EMAIL" => $HLEmail,
						"PERSONAL_PHONE" => $HLPhone,
						"GROUP_ID" => array(6),
					);
					$user->Update($userID, $userUpdateFields);
				}
				//$HLObjectClass::Delete($HLUserID);//Удаление списка добавленных пользователей из HighLoad блока если требуется
				unset($HLResultArr[$sysUserKey]);
			} else {

			}
		}
		foreach ($HLResultArr as $HLResultArrKey => $HLResultArrValue) {
			$HLID = $HLResultArrValue['ID'];
			$HLName = $HLResultArrValue['UF_NAME'];
			$HLSecondName = $HLResultArrValue['UF_SECOND_NAME'];
			$HLLastName = $HLResultArrValue['UF_LAST_NAME'];
			$HLEmail = $HLResultArrValue['UF_EMAIL'];
			$HLPhone = $HLResultArrValue['UF_PHONE'];
			$userAdd = new \CUser;
			$userAddFields = array(
				"NAME" => $HLName,
				"SECOND_NAME" => $HLSecondName,
				"LAST_NAME" => $HLLastName,
				"LOGIN" => $HLEmail,
				"EMAIL" => $HLEmail,
				"PERSONAL_PHONE" => $HLPhone,
				"GROUP_ID" => array(6),
				"LID" => "ru",
				"ACTIVE" => "Y",
				"PASSWORD" => "01012018",
				"CONFIRM_PASSWORD" => "01012018",
			);
			$ID = $userAdd->Add($userAddFields);
			if (IntVal($ID) > 0) {
				//$HLObjectClass::Delete($HLUserID);//Удаление списка добавленных пользователей из HighLoad блока если требуется
			} else {
				//$HLObjectClass::Delete($HLUserID);//Удаление списка добавленных пользователей из HighLoad блока если требуется
			}
		}
	}
} 



















