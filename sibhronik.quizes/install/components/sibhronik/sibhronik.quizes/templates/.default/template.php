<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?php
\Bitrix\Main\UI\Extension::load("ui.forms");
?>
<div class="quiz-menu-wrapper">
    <ul class="quiz-menu">
        <?php if ($_GET["quiz_add"] != "Y"):?>
            <li><a class="quiz-add-link" href="/sibhronik/?quiz_add=Y">Добавить опрос</a></li>
            <li><a class="quiz-list-link" href="/sibhronik/">Список опросов</a></li>
        <?php else: ?>
            <li><a class="quiz-list-link" href="/sibhronik/">Список опросов</a></li>
        <?php endif; ?>
    </ul>
</div>

<?php if ($_GET["quiz_add"] == "Y"):?>
    <div id="quiz-add-wrapper">
        <div class="quiz-main-title">Добавление опроса</div>
        <div class="quiz-add">
            <div class="quiz-add-head">
                <div class="quiz-add-title">Основные параметры опроса</div>
                <div class="quiz-add-caption">
                    <span class="quiz-add-groups">Установить опросы для групп пользователей</span>
                </div>
                <div class="quiz-add-caption ui-ctl ui-ctl-textbox">
                    <input id="quiz-add-name" class="quiz-add-head-input quiz-add-name ui-ctl-element" type="text" placeholder="Введите название опроса" />
                </div>
                <div class="quiz-add-caption ui-ctl ui-ctl-textarea">
                    <textarea id="quiz-add-description" class="quiz-add-head-input quiz-add-description ui-ctl-element ui-ctl-no-resize" type="textarea" placeholder="Введите описание опроса"></textarea>
                </div>
                <div class="quiz-add-caption">
                    <input id="quiz-add-date-start" class="quiz-add-date quiz-add-date-start" type="text" placeholder="22.02.2022 00:00:00" autocomplete="off" />
                    <label class="quiz-add-date-start-label" for="quiz-add-date-start">Установите дату начала опроса. Необязательно. По умолчанию - с момента создания опроса</label>
                </div>
                <div class="quiz-add-caption">
                    <input id="quiz-add-date-finish" class="quiz-add-date quiz-add-date-finish" type="text" placeholder="22.02.2022 00:00:00" autocomplete="off" />
                    <label class="quiz-add-date-finish-label" for="quiz-add-date-finish">Установите дату окончания опроса. Необязательно. По умолчанию - бессрочно</label>
                </div>
            </div><!--/.quiz-add-head-->
            <div id="quiz-add-body" class="quiz-add-body">
                <div class="quiz-add-titles">
                    <div class="quiz-add-title">Добавление вопросов</div>
                    <div class="quiz-add-title">Добавление ответов</div>
                </div>
                <div class="quiz-add-question-wrapper">
                    <div class="quiz-add-question">
                        <div class="quiz-add-caption ui-ctl ui-ctl-textbox">
                            <input class="quiz-add-input quiz-add-question-name ui-ctl-element" type="text" placeholder="Введите вопрос (обязательно)" />
                        </div>
                        <div class="quiz-add-caption">
                            <div class="quiz-add-select-title">Выберите тип вопроса</div>
                            <div class="quiz-add-select-wrapper ui-ctl ui-ctl-after-icon ui-ctl-dropdown">
                                <div class="ui-ctl-after ui-ctl-icon-angle"></div>
                                <select class="quiz-add-select ui-ctl-element">
                                    <option value="text">text</option>
                                    <option value="textarea">textarea</option>
                                    <option value="radio">radio</option>
                                    <option value="checkbox">checkbox</option>
                                </select>
                            </div>
                            <label class="quiz-add-checkbox-wrapper ui-ctl ui-ctl-checkbox">
                                <input class="quiz-add-checkbox ui-ctl-element" type="checkbox" />
                                <div class="ui-ctl-label-text">На вопрос требуется обязательный ответ</div>
                            </label>
                        </div>
                    </div>
                    <div class="quiz-add-answers-wrapper">
                        <div class="quiz-add-answers-wrap">
                            <div class="quiz-add-caption ui-ctl ui-ctl-textbox">
                                <div class="quiz-add-remove-answer">&times;</div>
                                <input class="quiz-add-input quiz-add-answer ui-ctl-element" type="text" placeholder="Введите ответ" />
                            </div>
                        </div>
                        <span id="quiz-add-answer-button" class="quiz-add-answer-button"">Добавить ответ</span>
                    </div>
                </div><!--/.quiz-add-question-wrapper-->
            </div><!--/.quiz-add-body-->
            <div class="quiz-add-footer">
                <span id="quiz-add-question-button" class="quiz-add-question-button" href="#">Добавить вопрос</span>
                <div class="quiz-add-caption">
                    <button id="quiz-add" class="ui-btn ui-btn-primary">Опубликовать опрос</button>
                </div>
            </div>
        </div><!--/.quiz-add-->
    </div><!--/#quiz-add-wrapper-->
<?php elseif ($_GET["quiz_id"] && intval($_GET["quiz_id"]) > 0): ?>
    <div class="quiz-item-wrapper">
        <?php $quizID = htmlspecialcharsEx(trim($_GET["quiz_id"])); ?>
        <?php $quiz = $arResult["QUIZES"][$quizID]; ?>
        <?php if ($quiz && strlen($quiz["NAME"]) > 0 && $quiz["ACTIVE"] == "1"): ?>
            <?php if (time() > strtotime($quiz["DATE_CREATED"])): ?>
                <?php if (count($quiz["QUESTIONS"]) > 0): ?>
                    <div class="quiz-item-title">Опрос «<?=$quiz["NAME"];?>»</div>
                    <?php echo strlen($quiz["DESCRIPTION"]) > 0 ? "<div class='quiz-item-description'>" . $quiz["DESCRIPTION"] . "</div>" : ""; ?>
                    <form id="quiz-item-result-form" action="javscript:void(null);">
                        <div class="quiz-item-questions-list">
                            <?php foreach ($quiz["QUESTIONS"] as $questionID => $question): ?>
                                <?php if (strlen(trim($question["NAME"])) > 0 && $question["ACTIVE"] == "1"): ?>
                                    <div class="quiz-item-question-wrapper <?php echo $question["TYPE"] == "checkbox" || $question["TYPE"] == "radio" && $question["REQUIRED"] == "1" ? "quiz-item-question-input-required" : "" ?>">
                                        <div class="quiz-item-question-name">
                                            <?=$question["NAME"];?>
                                            <?php if ($question["REQUIRED"] == "1"): ?>
                                                (обязательный ответ)
                                                <?php if ($question["TYPE"] == "checkbox" || $question["TYPE"] == "radio"): ?>
                                                    <div class="quiz-item-question-input-dropdown">Заполните это поле</div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (count($question["ANSWERS"]) > 0): ?>
                                            <?php foreach ($question["ANSWERS"] as $answerID => $answer): ?>
                                                <?php if (strlen(trim($answer["NAME"])) && $answer["ACTIVE"] == "1"): ?>
                                                    <div class="quiz-item-answer-wrapper">
                                                        <?php if (trim($question["TYPE"]) == "textarea"): ?>
                                                            <textarea id="answer-<?=$quizID;?>-<?=$questionID;?>-<?=$answerID;?>" class="quiz-item-answer quiz-item-answer-input-type" type="textarea" data-quiz-id="<?=$quizID;?>" data-question-id="<?=$questionID;?>" data-answer-id="<?=$answerID;?>"></textarea>
                                                        <?php elseif (trim($question["TYPE"]) == "radio"): ?>
                                                            <input
                                                                id="answer-<?=$quizID;?>-<?=$questionID;?>-<?=$answerID;?>"
                                                                class="quiz-item-answer quiz-item-answer-input-type"
                                                                name="answer-<?=$quizID;?>-<?=$questionID;?>?>"
                                                                type="<?=$question["TYPE"];?>"
                                                                data-quiz-id="<?=$quizID;?>"
                                                                data-question-id="<?=$questionID;?>"
                                                                data-answer-id="<?=$answerID;?>"
                                                            />
                                                        <?php elseif (trim($question["TYPE"]) == "checkbox"): ?>
                                                            <input
                                                                    id="answer-<?=$quizID;?>-<?=$questionID;?>-<?=$answerID;?>"
                                                                    class="quiz-item-answer quiz-item-answer-input-type"
                                                                    name="answer-<?=$quizID;?>-<?=$questionID;?>[]"
                                                                    type="<?=$question["TYPE"];?>"
                                                                    data-quiz-id="<?=$quizID;?>"
                                                                    data-question-id="<?=$questionID;?>"
                                                                    data-answer-id="<?=$answerID;?>"
                                                            />
                                                        <?php else: ?>
                                                            <input
                                                                id="answer-<?=$quizID;?>-<?=$questionID;?>-<?=$answerID;?>"
                                                                class="quiz-item-answer quiz-item-answer-input-type"
                                                                type="<?=$question["TYPE"];?>"
                                                                <?php echo $question["REQUIRED"] == "1" ? "required" : "";?>
                                                                data-quiz-id="<?=$quizID;?>"
                                                                data-question-id="<?=$questionID;?>"
                                                                data-answer-id="<?=$answerID;?>"
                                                            />
                                                        <?php endif; ?>
                                                        <label
                                                            for="answer-<?=$quizID;?>-<?=$questionID;?>-<?=$answerID;?>"
                                                            class="quiz-item-answer-name quiz-item-answer-name-<?=$question["TYPE"];?>">
                                                            <?=$answer["NAME"];?>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php else: ?>

                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <input id="quiz-item-answer-result-button" class="ui-btn ui-btn-primary" type="submit" value="Сохранить" />
                    </form>
                <?php else: ?>
                    <div class="warning-notice">У опроса нет вопросов</div>
                <?php endif; ?>
            <?php else: ?>
                <div class="warning-notice">Опрос окончен</div>
            <?php endif; ?>
        <?php else: ?>
            <div class="warning-notice">Опрос отсутствует</div>
        <?php endif; ?>
    </div>
<?php elseif ($_GET["quiz_id_results"] && intval($_GET["quiz_id_results"]) > 0): ?>
    <div id="quiz-results-wrapper">
        <?php $quizID = htmlspecialcharsEx($_GET["quiz_id_results"]); ?>
        <?php if ($arResult["USERS_ANSWERS"][$quizID] && count($arResult["USERS_ANSWERS"][$quizID]) > 0): ?>
            <div class="quiz-main-title">Результаты опроса «<?=$arResult["USERS_ANSWERS"][$quizID]["NAME"];?>»</div>
            <div class="quiz-main-description"><?=$arResult["USERS_ANSWERS"][$quizID]["DESCRIPTION"];?></div>
            <?php if ($arResult["USERS_ANSWERS"][$quizID]["USERS"] && count($arResult["USERS_ANSWERS"][$quizID]["USERS"]) > 0): ?>
                <?php foreach ($arResult["USERS_ANSWERS"][$quizID]["USERS"] as $userID => $arQuestionName): ?>
                    <div class="quiz-result-wrapper">
                    <?php
                    $queryUser = Bitrix\Main\UserTable::getList([
                        "filter" => ["ID" => $userID]
                    ]) -> fetch();
                    ?>
                    <div class="quiz-results-user"><?=$queryUser["LAST_NAME"] . " " . $queryUser["NAME"];?></div>
                    <?php foreach ($arQuestionName as $questionName => $arAnswerName): ?>
                        <?php if ($questionName && trim($questionName) != ""): ?>
                        <div class="quiz-results-question-name"><?=$questionName;?></div>
                            <?php foreach ($arAnswerName as $answerName => $answerData): ?>
                                <?php if ($questionName && trim($questionName) != ""): ?>
                                    <div class="quiz-results-type"><?php if ($answerData["TYPE"] == "text" || $answerData["TYPE"] == "textarea") echo $answerName; ?></div>
                                    <?php if (trim($answerData["TYPE"]) != ""): ?>
                                        <?php
                                        switch (trim($answerData["TYPE"])) {
                                            case "text":
                                                if (trim($answerData["VALUE"]) != "") { ?>
                                                    <div class="quiz-results-caption ui-ctl ui-ctl-textbox ui-ctl-disabled">
                                                        <input type="text" class="quiz-add-head-input quiz-add-name ui-ctl-element" value="<?=trim($answerData["VALUE"]);?>" />
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="quiz-results-caption ui-ctl ui-ctl-textbox ui-ctl-disabled">
                                                        <input type="text" class="quiz-add-head-input quiz-add-name ui-ctl-element" value="" placeholder="Пользователь не ответил на этот вопрос" />
                                                    </div>
                                                <?php } ?>
                                            <?php break; ?>
                                            <?php case "textarea":
                                                if (trim($answerData["VALUE"]) != "") { ?>
                                                    <div class="quiz-results-caption ui-ctl ui-ctl-textarea ui-ctl-disabled">
                                                        <textarea type="textarea" class="quiz-add-head-input quiz-add-name ui-ctl-element"><?=trim($answerData["VALUE"]);?></textarea>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="quiz-results-caption ui-ctl ui-ctl-textarea ui-ctl-disabled">
                                                        <textarea type="textarea" class="quiz-add-head-input quiz-add-name ui-ctl-element" placeholder="Пользователь не ответил на этот вопрос"></textarea>
                                                    </div>
                                                <?php } ?>
                                            <?php break; ?>
                                            <?php case "radio":
                                                if ($answerData["TYPE"] && trim($answerData["VALUE"]) != "") { ?>
                                                    <div class="quiz-item-answer-wrapper">
                                                        <input class="quiz-item-answer quiz-item-answer-input-type" type="radio" checked="checked" disabled />
                                                        <label class="quiz-item-answer-name quiz-item-answer-name-radio"><?=trim($answerName);?></label>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="quiz-item-answer-wrapper">
                                                        <input type="radio" disabled />
                                                        <label class="quiz-item-answer-name quiz-item-answer-name-radio"><?=trim($answerName);?></label>
                                                    </div>
                                                <?php } ?>
                                            <?php break; ?>
                                            <?php case "checkbox":
                                                if ($answerData["TYPE"] && trim($answerData["VALUE"]) != "") { ?>
                                                    <div class="quiz-item-answer-wrapper">
                                                        <input class="quiz-item-answer quiz-item-answer-input-type" type="checkbox" checked="checked" disabled />
                                                        <label class="quiz-item-answer-name quiz-item-answer-name-radio"><?=trim($answerName);?></label>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="quiz-item-answer-wrapper">
                                                        <input class="quiz-item-answer quiz-item-answer-input-type" type="checkbox" disabled />
                                                        <label class="quiz-item-answer-name quiz-item-answer-name-radio"><?=trim($answerName);?></label>
                                                    </div>
                                                <?php } ?>
                                            <?php break; ?>
                                        <?php } ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php else: ?>
            <div class="warning-notice">Такого опроса не существует</div>
        <?php endif; ?>
    </div>
<?php else: ?>
    <?php// printArr($arResult); ?>
    <div id="quiz-list-wrapper">
        <?php if (count($arResult["QUIZES"]) > 0): ?>
        <div class="quiz-main-title">Список опросов</div>
        <div class="quiz-list">
            <?php foreach ($arResult["QUIZES"] as $quizIndex => $quiz): ?>
            <div class="quiz-list-item-wrapper">
                <div class="quiz-list-item">
                    <?php if (in_array($arResult["CURRENT_USER"], $arResult["QUIZ_ANSWERED_USERS"][$quizIndex])): ?>
                        <div class="quiz-title-success"><?=$quiz["NAME"];?> <i>(опрос пройден)</i></div>
                    <?php else: ?>
                        <div class="quiz-title"><a href="/sibhronik/?quiz_id=<?=$quiz["ID"];?>"><?=$quiz["NAME"];?></a></div>
                    <?php endif; ?>
                    <div class="quiz-description"><?=$quiz["DESCRIPTION"];?></div>
                    <div class="quiz-details-link"><a href="/sibhronik/?quiz_id_results=<?=$quiz["ID"];?>">Результаты опроса</a></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <div class="quiz-main-title">Не создано ни одного опроса</div>
        <?php endif; ?>
    </div>
<?php endif; ?>
