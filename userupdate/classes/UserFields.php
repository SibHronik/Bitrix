<?php
namespace Strlog\Userupdate\Classes;

class UserFields
{
	function getUserFields()
	{
		$arEditFields = [];
		$editFields = array(
			"ID",
			"TITLE",
			"NAME",
			"LAST_NAME",
			"SECOND_NAME",
			"EMAIL",
			"LOGIN",
			"PERSONAL_PROFESSION",
			"PERSONAL_PHONE",
			/*"PERSONAL_WWW",
			"PERSONAL_ICQ",
			"PERSONAL_GENDER",
			"PERSONAL_BIRTHDAY",
			"PERSONAL_FAX",
			"PERSONAL_MOBILE",
			"PERSONAL_PAGER",
			"PERSONAL_STREET",
			"PERSONAL_MAILBOX",
			"PERSONAL_CITY",
			"PERSONAL_STATE",
			"PERSONAL_ZIP",
			"PERSONAL_COUNTRY",
			"PERSONAL_NOTES",
			"WORK_COMPANY",
			"WORK_DEPARTMENT",
			"WORK_POSITION",
			"WORK_WWW",
			"WORK_PHONE",
			"WORK_FAX",
			"WORK_PAGER",
			"WORK_STREET",
			"WORK_MAILBOX",
			"WORK_CITY",
			"WORK_STATE",
			"WORK_ZIP",
			"WORK_COUNTRY",
			"WORK_PROFILE",
			"WORK_NOTES",*/
		);
		foreach ($editFields as $key => $value) {
			$arEditFields[$value] = $value;
		}
		return $arEditFields;
	}
}