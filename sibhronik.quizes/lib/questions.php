<?php

namespace Sibhronik\Quiz;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\Validator;

class QuestionsTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return "sibhronik_questions";
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
            new Entity\BooleanField("ACTIVE", [
                "default_value" => true
            ]),
            new Entity\EnumField("TYPE", [
                "values" => ["text", "textarea", "radio", "checkbox"],
                "default_value" => "text"
            ]),
            new Entity\StringField("NAME", [
                "required" => true,
                "validation" => function () {
                    return [
                        new Validator\Length(null, 255)
                    ];
                }
            ]),
            new Entity\BooleanField("REQUIRED"),
        ];
    }
}