<?php
namespace Strlog\Userupdate\Classes;

\Bitrix\Main\Loader::includeModule('highloadblock');
use \Bitrix\Highloadblock as HL;

class CheckUsers
{
	public static function MainCheck($highloadID)
	{
		$arHLs = [];
        $HLBlock = HL\HighloadBlockTable::getById($highloadID)->fetch();
        $HLObject = HL\HighloadBlockTable::compileEntity($highloadID);
        $HLObjectClass = $HLObject->getDataClass();
        $HLResultArray = $HLObjectClass::getList(array(
            'select' => array('*'),
        ));
        while ($HLResult = $HLResultArray->fetch()) {
            $arHLs[] = $HLResult;
        }
        return $arHLs;
	}
	public static function CheckArrays($highloadID)
	{
		echo 'Пользователи HighLoad блока: ';
		echo '<pre>';
		print_r(self::MainCheck($highloadID));
		echo '</pre>';
	}
	public static function CheckCount($highloadID)
	{
		return count(self::MainCheck($highloadID));
	}
}