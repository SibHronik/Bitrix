<?php

namespace Sibhronik\Quiz;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\Validator;

class QuizTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return "sibhronik_quiz";
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField("ID", [
                "primary" => true,
                "autocomplete" => true
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
            new Entity\StringField("DESCRIPTION", []),
            new Entity\DateTimeField("DATE_CREATED", []),
            new Entity\StringField("DATE_FINISH")
        ];
    }
}