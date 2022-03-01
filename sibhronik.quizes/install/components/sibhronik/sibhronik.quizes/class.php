<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

\Bitrix\Main\Loader::includeModule("sibhronik.quizes");

use Bitrix\Main\Loader;
use Sibhronik\Quizes;
use Sibhronik\Quizes\QuizesTable;
use Sibhronik\Quizes\QuestionsTable;
use Sibhronik\Quizes\AnswersTable;
use Sibhronik\Quizes\UsersAnsweredTable;
use Bitrix\Main\Type\DateTime;

class SibhronikQuiz extends CBitrixComponent
{
    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    private function getQuizesList()
    {
        $quizes = [];
        $quizAnsweredUsers = [];
        $queryQuizes = Sibhronik\Quizes\QuizesTable::GetList();
        while ($quiz = $queryQuizes -> fetch()) {
            $quiz["DATE_CREATED"] = $quiz["DATE_CREATED"] == NULL ? "" : $quiz["DATE_CREATED"] -> format("d.m.Y H:i:s");
            $quizes[$quiz["ID"]] = $quiz;
            if ($quiz["ID"] && intval($quiz["ID"]) > 0) {
                //Получаем уже ответивших на этот опрос пользователей
                $getQuizAnsweredUsers = UsersAnsweredTable::GetList([
                    "select" => ["USER_ID"],
                    "filter" => ["QUIZ_ID" => $quiz["ID"]]
                ]);
                while ($getQuizAnsweredUser = $getQuizAnsweredUsers -> fetch()) {
                    $quizAnsweredUsers[$quiz["ID"]][] = $getQuizAnsweredUser["USER_ID"];
                }
                $queryQuestions = Sibhronik\Quizes\QuestionsTable::GetList([
                    "select" => ["*"],
                    "filter" => ["QUIZ_ID" => $quiz["ID"]]
                ]);
                while ($question = $queryQuestions -> fetch()) {
                    $quizes[$quiz["ID"]]["QUESTIONS"][$question["ID"]] = $question;
                    if ($question["ID"] && intval($question["ID"]) > 0) {
                        $queryAnswers = Sibhronik\Quizes\AnswersTable::GetList([
                            "select" => ["*"],
                            "filter" => ["QUIZ_ID" => $quiz["ID"], "QUESTION_ID" => $question["ID"]]
                        ]);
                        while ($answer = $queryAnswers -> fetch()) {
                            $quizes[$quiz["ID"]]["QUESTIONS"][$question["ID"]]["ANSWERS"][$answer["ID"]] = $answer;
                            if ($answer["ID"] && intval($answer["ID"]) > 0) {
                                $queryUserAnswers = Sibhronik\Quizes\UserAnswersTable::GetList([
                                    "select" => ["*"],
                                    "filter" => ["QUIZ_ID" => $quiz["ID"], "QUESTION_ID" => $question["ID"], "ANSWER_ID" => $answer["ID"]]
                                ]);
                                while ($userAnswer = $queryUserAnswers -> fetch()) {
                                    $quizes[$quiz["ID"]]["QUESTIONS"][$question["ID"]]["ANSWERS"][$answer["ID"]]["USER_ANSWERS"][$userAnswer["ID"]] = $userAnswer;
                                }
                            }
                        }
                    }
                }
            }
        }
        $quizesData["QUIZ_ANSWERED_USERS"] = $quizAnsweredUsers;
        $quizesData["QUIZES"] = $quizes;
        return $quizesData;
    }

    private function getQuizesAnsweres()
    {
        $quizUserAnswers = [];
        $queryQuizes = Sibhronik\Quizes\QuizesTable::GetList();
        while ($quiz = $queryQuizes -> fetch()) {
            $quiz["DATE_CREATED"] = $quiz["DATE_CREATED"] -> format("d.m.Y H:i:s");
            $quizUserAnswers[$quiz["ID"]] = $quiz;
            $queryUserAnswers = Sibhronik\Quizes\UserAnswersTable::GetList([
                "select" => ["*"],
                "filter" => ["QUIZ_ID" => $quiz["ID"]]
            ]);
            while ($userAnswer = $queryUserAnswers -> fetch()) {
                $queryQuestions = Sibhronik\Quizes\QuestionsTable::GetList([
                    "select" => ["*"],
                    "filter" => ["ID" => $userAnswer["QUESTION_ID"]]
                ]);
                while ($question = $queryQuestions -> fetch()) {
                    $queryAnswers = Sibhronik\Quizes\AnswersTable::GetList([
                        "select" => ["*"],
                        "filter" => ["QUIZ_ID" => $quiz["ID"], "QUESTION_ID" => $question["ID"]]
                    ]);
                    while ($answer = $queryAnswers->fetch()) {
                        $secondQueryUserAnswers = Sibhronik\Quizes\UserAnswersTable::GetList([
                            "select" => ["*"],
                            "filter" => ["QUIZ_ID" => $quiz["ID"], "QUESTION_ID" => $question["ID"], "ANSWER_ID" => $answer["ID"], "USER_ID" => $userAnswer["USER_ID"]]
                        ]);
                        if ($secondQueryUserAnswers -> getSelectedRowsCount() > 0) {
                            while ($secondUserAnswer = $secondQueryUserAnswers->fetch()) {
                                $secondUserAnswer["TYPE"] = $question["TYPE"];
                                $quizUserAnswers[$quiz["ID"]]["USERS"][$userAnswer["USER_ID"]][$question["NAME"]][$answer["NAME"]] = $secondUserAnswer;
                            }
                        } else {
                            $quizUserAnswers[$quiz["ID"]]["USERS"][$userAnswer["USER_ID"]][$question["NAME"]][$answer["NAME"]]["TYPE"] = $question["TYPE"];
                        }
                    }
                }
            }
        }
        return $quizUserAnswers;
    }

    private function currentUser()
    {
        global $USER;
        return $USER -> GetID();

    }

    public function executeComponent()
    {
        try {
            if ($this -> currentUser() > 0) {
                $this -> arResult = $this -> getQuizesList();
                $this -> arResult["USERS_ANSWERS"] = $this -> getQuizesAnsweres();
                $this -> arResult["CURRENT_USER"] = $this -> currentUser();
                $this -> includeComponentTemplate();
            } else {
                global $USER;
                if ($USER -> IsAdmin()) {
                    echo "Пользователь не определен";
                }
            }
        } catch (Exception $error) {
            global $USER;
            if ($USER -> IsAdmin()) {
                print_r($error -> getMessage());
            }
        }
    }
}