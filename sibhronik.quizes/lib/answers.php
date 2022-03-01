<?php

namespace Sibhronik\Quizes;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\Validator;

class AnswersTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return "sibhronik_answers";
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
            new Entity\BooleanField("ACTIVE", [
                "default_value" => true
            ]),
            new Entity\StringField("NAME", [
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