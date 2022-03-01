<?php

namespace Sibhronik\Quizes;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\Validator;

class UsersAnsweredTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return "sibhronik_users_answered";
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField("ID", [
                "primary" => true,
                "autocomplete" => true
            ]),
            new Entity\IntegerField("QUIZ_ID", [
                "required" => true
            ]),
            new Entity\IntegerField("USER_ID", [
                "required" => true
            ]),
            new Entity\DateTimeField("DATE_CREATED", []),
        ];
    }
}