<?php

namespace Sibhronik\Quiz;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\Validator;

class UserAnswersTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return "sibhronik_user_answers";
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
            new Entity\IntegerField("QUESTION_ID", [
                "required" => true
            ]),
            new Entity\IntegerField("ANSWER_ID", [
                "required" => true
            ]),
            new Entity\IntegerField("USER_ID", [
                "required" => true
            ]),
            new Entity\BooleanField("ACTIVE", [
                "default_value" => true
            ]),
            new Entity\StringField("VALUE", [
                "required" => true,
                "validation" => function () {
                    return [
                        new Validator\Length(null, 255)
                    ];
                }
            ]),
        ];
    }
}