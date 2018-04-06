<?php
namespace Strlog\Userupdate\Classes;

\Bitrix\Main\Loader::includeModule('highloadblock');
use \Bitrix\Highloadblock as HL;

class HLFields
{
	function getHLFields($highload_id)
	{
		$HLBlockKeysArray = [];
		$HLBlockKeys = [];
		$HLBlock = HL\HighloadBlockTable::getById($highload_id)->fetch();
		$HLObject = HL\HighloadBlockTable::compileEntity($HLBlock);
		$HLObjectClass = $HLObject->getDataClass();
		$HLResultArray = $HLObjectClass::getList(array(
			'select' => array('*'),
		));
		while ($HLResult = $HLResultArray->fetch()) {
			$HLBlockKeysArray = $HLResult;
		}
		$HLBlockKeysArray = array_keys($HLBlockKeysArray);
		foreach ($HLBlockKeysArray as $key => $value) {
			$HLBlockKeys[$value] = $value;
		}
		return $HLBlockKeys;
	}
}