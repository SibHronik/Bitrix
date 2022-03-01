<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?php
\Bitrix\Main\Loader::includeModule("sibhronik.quizes");
use Bitrix\Main\Loader;
use Sibhronik\Quizes;
use Sibhronik\Quizes\QuizesTable;
use Sibhronik\Quizes\QuestionsTable;
use Sibhronik\Quizes\AnswersTable;
use Sibhronik\Quizes\UserAnswersTable;
use Sibhronik\Quizes\UsersAnsweredTable;
use Bitrix\Main\Type\DateTime;

class SibhronikQuizActions extends \Bitrix\Main\Engine\Controller
{
    public function SetQuizAction()
    {
        /**
         * Создание опроса
         * return array $result
         */
        $quizName = htmlspecialcharsEx($_REQUEST["QUIZ"]["NAME"]);
        $quizDescription = htmlspecialcharsEx($_REQUEST["QUIZ"]["DESCRIPTION"]);
        $quizDateCreated = htmlspecialcharsEx($_REQUEST["QUIZ"]["DATE_CREATED"]);
        if (is_numeric(strtotime($quizDateCreated))) {
            $quizDateCreated = DateTime::createFromPhp(new \DateTime(date("Y-m-d H:i:s", strtotime($quizDateCreated))));
            //return $quizDateCreated;
        } else {
            $quizDateCreated = DateTime::createFromPhp(new \DateTime(date("Y-m-d H:i:s", time())));
        }
        $quizDateFinish = htmlspecialcharsEx($_REQUEST["QUIZ"]["DATE_FINISH"]);
        if (is_numeric(strtotime($quizDateFinish))) {
            $quizDateFinish = date("d.m.Y H:i:s", strtotime($quizDateFinish));
        }
        if (trim($quizName) != "") {
            $addQuiz = Sibhronik\Quizes\QuizesTable::add([
                "ACTIVE" => 1,
                "NAME" => $quizName,
                "DESCRIPTION" => $quizDescription,
                "DATE_CREATED" => $quizDateCreated,
                "DATE_FINISH" => $quizDateFinish,
            ]);
            if ($addQuiz -> isSuccess()) {
                if (intval($addQuiz -> getId()) > 0) {
                    $quizID = intval($addQuiz -> getId());
                    if (count($_REQUEST["QUIZ"]["QUESTIONS"]) > 0) {
                        foreach ($_REQUEST["QUIZ"]["QUESTIONS"] as $questionIndex => $question) {
                            $addQuestion = Sibhronik\Quizes\QuestionsTable::add([
                                "QUIZ_ID" => $quizID,
                                "ACTIVE" => 1,
                                "NAME" => htmlspecialcharsEx($question["NAME"]),
                                "TYPE" => htmlspecialcharsEx($question["TYPE"]),
                                "REQUIRED" => htmlspecialcharsEx($question["REQUIRED"]),
                            ]);
                            if ($addQuestion -> isSuccess()) {
                                if (intval($addQuestion -> getId()) > 0) {
                                    $questionID = intval($addQuestion -> getId());
                                    if (count($question["ANSWERS"]) > 0) {
                                        foreach ($question["ANSWERS"] as $answerIndex => $answer) {
                                            $addAnswer = Sibhronik\Quizes\AnswersTable::add([
                                                "QUIZ_ID" => $quizID,
                                                "QUESTION_ID" => $questionID,
                                                "ACTIVE" => 1,
                                                "NAME" => $answer
                                            ]);
                                            if ($addAnswer -> isSuccess()) {

                                            } else {
                                                return $addAnswer -> getErrorMessages();
                                            }
                                        }
                                    }
                                }
                            } else {
                                return $addQuestion -> getErrorMessages();
                            }
                        }
                    } else {
                        return "Error! No questions";
                    }
                } else {
                    return "ERROR";
                }
            } else {
                return $addQuiz -> getErrorMessages();
            }
        } else {
            //Ошибка. Нет названия опроса
        }
        //return $_REQUEST;
    }

    public function SetAnswerAction()
    {
        $successUserID = "";
        $successQuizID = "";
        if ($_REQUEST["USER_ANSWERS"] && count($_REQUEST["USER_ANSWERS"]) > 0) {
            global $USER;
            $userID = $USER -> GetID();
            if ($userID && intval($userID) > 0) {
                $successUserID = $userID;
                foreach ($_REQUEST["USER_ANSWERS"] as $quizID => $arQuestions) {
                    $quizID = htmlspecialcharsEx($quizID);
                    if (intval($quizID) > 0) {
                        $successQuizID = $quizID;
                        foreach ($arQuestions as $questionID => $arAnswers) {
                            $questionID = htmlspecialcharsEx($questionID);
                            if (intval($questionID) > 0) {
                                foreach ($arAnswers as $answerID => $arAnswer) {
                                    $answerID = htmlspecialcharsEx($answerID);
                                    if (intval($answerID) > 0) {
                                        $addUserAnswers = UserAnswersTable::add([
                                            "QUIZ_ID" => $quizID,
                                            "QUESTION_ID" => $questionID,
                                            "ANSWER_ID" => $answerID,
                                            "USER_ID" => $userID,
                                            "ACTIVE" => 1,
                                            "VALUE" => $arAnswer,
                                        ]);
                                        if ($addUserAnswers -> isSuccess()) {
                                            $success = $userID;
                                        } else {
                                            return $addUserAnswers -> getErrorMessages();
                                        }
                                    }
                                }
                            } else {
                                return ["ERROR" => "Вопрос не записан"];
                            }
                        }
                    } else {
                        return ["ERROR" => "Опрос не записан"];
                    }
                }
            } else {
                return ["ERROR" => "Пользователь не найден"];
            }
        }
        if ($successQuizID &&
            intval($successQuizID) > 0 &&
            $successUserID &&
            intval($successUserID) > 0
        ) {
            $addUserAnswered = UsersAnsweredTable::add([
                "QUIZ_ID" => $successQuizID,
                "USER_ID" => $successUserID,
                "DATE_CREATED" => DateTime::createFromPhp(new \DateTime(date("Y-m-d H:i:s", time())))
            ]);
            if ($addUserAnswered->isSuccess()) {

            } else {
                return $addUserAnswered->getErrorMessages();
            }
        }
    }
}
?>
