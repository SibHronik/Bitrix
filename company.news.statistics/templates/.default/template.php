<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?php
if (count($arResult["ERRORS"]) > 0) : ?>
    <?php foreach ($arResult["ERRORS"] as $error): ?>
        <div class='news-statistics-error'><?=$error;?></div>
    <?php endforeach; ?>
<?php endif; ?>

<?php
use \Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
Loc::loadMessages(__FILE__);
CJSCore::Init(["popup"]);
Asset::getInstance()->addJs("https://cdn.amcharts.com/lib/4/core.js");
Asset::getInstance()->addJs("https://cdn.amcharts.com/lib/4/charts.js");
Asset::getInstance()->addJs("https://cdn.amcharts.com/lib/4/themes/animated.js");
?>
<div id="news-statistics-wrapper" class="news-statistics-wrapper">
    <div id="news-statistics-title" class="news-statistics-title"><?=Loc::getMessage("MAIN_TITLE");?></div>
    <div class="news-statistics-content">
        <div class="news-statistics-tags-wrapper">
            <div class="news-statistics-aside-title"><?=Loc::getMessage("CONTROL_TAGS_TITLE");?></div>
            <?php if (count($arResult["TAGS"]) > 0): ?>
            <ul class="news-statistics-tags">
                <?php foreach ($arResult["TAGS"] as $tagID => $tagValue): ?>
                <li id="news-statistics-tag-li-<?=$tagValue["ID"];?>" class="news-statistics-tag-li">
                    <div class="news-statistics-tag">
                        <span class="news-statistics-tag-title"><?=$tagValue["NAME"]?></span>
                        <span class="news-statistics-tag-delete" data-tag-id="<?=$tagValue["ID"];?>" data-tag-name="<?=$tagValue["NAME"];?>"><?=Loc::getMessage("DELETE_TAG_TITLE");?></span>
                    </div>
                    <div class="news-statistics-tag-info">
                        <span class="news-statistics-tag-info-count"><?=Loc::getMessage("USE_IN");?> <?=$tagValue["TAG_COUNT"];?> <?=Loc::getMessage("POST");?><?=$tagValue["TAG_COUNT"] == 1 ? Loc::getMessage("POST_END_1") : Loc::getMessage("POST_END_2");?></span>
                        <?php if ($tagValue["TAG_COUNT"] > 0): ?>
                        <span class="news-statistics-tag-info-posts" data-tag-id="<?=$tagValue["ID"];?>"><?=Loc::getMessage("POST_LIST");?></span>
                        <?php endif; ?>
                    </div>
                    <div id="news-statistics-tag-delete-success-<?=$tagValue["ID"];?>" class="news-statistics-tag-delete-success"><?=Loc::getMessage("POST_DELETED");?></div>
                    <div id="news-statistics-tag-delete-error-<?=$tagValue["ID"];?>" class="news-statistics-tag-delete-error"><?=Loc::getMessage("POST_DELETED_ERROR");?></div>
                    <div id="news-statistics-posts-wrapper-<?=$tagValue["ID"];?>" class="news-statistics-posts-wrapper">
                        <ul>
                        <?php foreach ($tagValue["POSTS"] as $postID => $postName): ?>
                        <li><a href="/company/personal/user/<?=$arResult["CURRENT_USER"]["ID"];?>/blog/<?=$postID?>/"><?=$postName;?></a></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <div class="tags-not-found"><?=Loc::getMessage("TAGS_NOT_FOUND");?></div>
            <?php endif; ?>
        </div>
        <div class="news-statistics-info-content">
            <div id="news-statistics-info-wrapper" class="news-statistics-info-wrapper">
                <div id="news-statistics-info-wrapper-clear" class="news-statistics-info-wrapper-clear" title="Очистить поле">&times;</div>
                <div id="news-statistics-info" class="news-statistics-info"></div>
            </div>
            <div class="news-statistics-info-tops-wrapper">
                <div class="news-statistics-info-tops-menu-wrappper">
                    <ul class="news-statistics-info-tops-menu">
                        <li class="news-statistics-info-menu-top-point news-statistics-info-menu-top-comments news-statistics-info-menu-active" data-info-point="top-comments"><?=Loc::getMessage("TOP_COMMENTED_POST");?></li>
                        <li class="news-statistics-info-menu-top-point news-statistics-info-menu-top-votes" data-info-point="top-votes"><?=Loc::getMessage("TOP_LIKED_POST");?></li>
                        <li class="news-statistics-info-menu-top-point news-statistics-info-menu-top-views" data-info-point="top-views"><?=Loc::getMessage("TOP_VIEWED_POST");?></li>
                    </ul>
                </div>
                <div id="news-statistics-info-top-comments-wrapper" class="news-statistics-info-top news-statistics-info-top-comments-wrapper" data-info-point="top-comments">
                    <?if (count($arResult["TOP_COMMENTS"]) > 0) : ?>
                    <ul class="news-statistics-info-tops-list">
                        <?php foreach ($arResult["TOP_COMMENTS"] as $topCommentKey => $topCommentValue): ?>
                        <li>
                            <span class="news-statistics-info-tops-list-title"><a href="/company/personal/user/<?=$arResult["CURRENT_USER"]["ID"];?>/blog/<?=$topCommentKey?>/"><?=$topCommentValue["NAME"];?></a>.</span>
                            <span class="news-statistics-info-tops-list-quantity"><i><?=Loc::getMessage("COMMENTS_QUANTITY");?> <?=$topCommentValue["TOTAL_COMMENTS"];?></i></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <div id="news-statistics-info-top-votes-wrapper" class="news-statistics-info-top news-statistics-info-top-votes-wrapper" data-info-point="top-votes">
                    <?if (count($arResult["TOP_VOTES"]) > 0) : ?>
                    <ul class="news-statistics-info-tops-list">
                        <?php foreach ($arResult["TOP_VOTES"] as $topVoteKey => $topVoteValue): ?>
                        <li>
                            <span class="news-statistics-info-tops-list-title"><a href="/company/personal/user/<?=$arResult["CURRENT_USER"]["ID"];?>/blog/<?=$topVoteKey?>/"><?=$topVoteValue["NAME"];?></a>. </span>
                            <span class="news-statistics-info-tops-list-quantity <?=count($topVoteValue["USERS"]) > 0 ? "news-statistics-info-tops-list-dropdown" : ""?>" data-post-id="<?=$topVoteKey;?>">
                                <span class="dropdown-arrow" data-post-id="<?=$topVoteKey;?>">Кол-во лайков: <?=$topVoteValue["TOTAL_VOTES"];?> <?=count($topVoteValue["USERS"]) > 0 ? "&#8595;" : ""?></span>
                            </span>
                            <?php if (count($topVoteValue["USERS"]) > 0): ?>
                                <ul id="news-statistics-info-top-votes-user-list-wrapper-<?=$topVoteKey;?>" class="news-statistics-info-top-votes-user-list-wrapper">
                                    <?php foreach ($topVoteValue["USERS"] as $voteUserID => $voteUserName): ?>
                                        <li><a href="/company/personal/user/<?=$voteUserID;?>/"><?=$voteUserName;?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?endif;?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <div id="news-statistics-info-top-views-wrapper" class="news-statistics-info-top news-statistics-info-top-views-wrapper" data-info-point="top-views">
                    <?if (count($arResult["TOP_VIEWS"]) > 0) : ?>
                    <ul class="news-statistics-info-tops-list">
                        <?php foreach ($arResult["TOP_VIEWS"] as $topViewsKey => $topViewsValue): ?>
                        <li>
                            <span class="news-statistics-info-tops-list-title"><a href="/company/personal/user/<?=$arResult["CURRENT_USER"]["ID"];?>/blog/<?=$topViewsKey?>/"><?=$topViewsValue["NAME"];?></a>. </span>
                            <span class="news-statistics-info-tops-list-quantity"><i>Кол-во просмотров: <?=$topViewsValue["TOTAL_VIEWS"];?></i></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (count($arResult["TOP_COMMENTATORS"]) > 0): ?>
            <div class="news-statistics-post-comments-wrapper">
                <div class="news-statistics-info-title"><?=Loc::getMessage("TOP_COMMENTATORS");?></div>
                <ul class="news-statistics-post-comments-list">
                    <?php foreach ($arResult["TOP_COMMENTATORS"] as $userID => $postValue): ?>
                    <li>
                        <a href="/company/personal/user/<?=$userID;?>/"><?=$postValue["USER"];?></a>.
                        <i><?=Loc::getMessage("COMMENTS");?> <?=$postValue["COUNT"];?></i>.
                        <span class="news-statistics-comments-detail-list" data-user-id="<?=$userID;?>"><?=Loc::getMessage("GET_DETAILS");?> <span class="arrow">&#8595;</span></span>
                        <?php if (count($postValue["POSTS"]) > 0 && intval($postValue["COUNT"]) > 0): ?>
                        <ul id="news-statistics-commentators-list-wrapper-<?=$userID;?>" class="news-statistics-commentators-list-wrapper">
                            <?php foreach ($postValue["POSTS"] as $postID => $commentData): ?>
                            <li>
                                <?=$commentData["POST_NAME"];?>. <i><?=Loc::getMessage("COMMENTS");?> <?=count($commentData["USER_POST_COMMENTS"]);?></i>.
                                <span class="news-statistics-comment-detail" data-post-id="<?=$postID;?>"><?=Loc::getMessage("GET_DETAILS");?> <span class="arrow">&#8595;</span></span>
                                <?php if (count($commentData["USER_POST_COMMENTS"]) > 0): ?>
                                <ul id="news-statistics-commentators-list-<?=$postID;?>" class="news-statistics-commentators-list">
                                    <?php foreach ($commentData["USER_POST_COMMENTS"] as $commentID => $commentValue): ?>
                                    <li><?=$commentValue;?>
                                        <a
                                            class="news-statistics-comment-link"
                                            href="/company/personal/user/<?=$postValue["AUTHOR_ID"];?>/blog/<?=$commentData["POST_ID"];?>/?commentId=<?=$commentID;?>#com<?=$commentID;?>">
                                            <?=Loc::getMessage("GO_TO_COMMENT");?> <span class="arrow">&#8594;</span>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            <div class="news-statistics-set-post-preview-text-wrapper">
                <div class="news-statistics-set-post-preview-text-title">Список постов</div>
                <?php if (count($arResult["POSTS"]) > 0): ?>
                    <?php $arPosts = array_chunk($arResult["POSTS"], 10); ?>
                    <?php $countPosts = 0; ?>
                    <?php foreach ($arPosts as $postChunkKey => $arPostData): ?>
                        <?php $arPostPieces = intVal($postChunkKey) + 1; ?>
                        <?php if (count($arPostData) > 0): ?>
                            <ul id="news-statistics-set-post-preview-text-list-<?=$arPostPieces;?>" class="news-statistics-set-post-preview-text-list">
                                <?php foreach ($arPostData as $postKey => $postData): ?>
                                    <?php $countPosts += 1; ?>
                                    <li>
                                        <span class="news-statistics-set-post-preview-text-name">
                                            <span class="news-statistics-set-post-preview-text-name-count">
                                                <?=$countPosts . "</span>. " . $postData["TITLE"];?>
                                        </span>
                                        <span class="news-statistics-set-post-preview-text-date"> Опубликовано: <?=$postData["DATE_CREATE"];?></span>
                                        <div class="news-statistics-set-post-preview-text-dropdown">
                                            <span class="set-post-preview-text-dropdown-button" data-post-id="<?=$postData["ID"];?>">
                                                Задать текст анонса
                                            </span>
                                        </div>
                                            <div
                                                id="news-statistics-set-preview-text-area-wrapper-<?=$postData["ID"];?>"
                                                class="news-statistics-set-preview-text-area-wrapper">
                                                <textarea data-post-id="<?=$postData["ID"];?>"
                                                      id="news-statistics-set-preview-text-area-<?=$postData["ID"];?>"
                                                      class="news-statistics-set-preview-text-area"
                                                      value="<?=$postData["PREVIEW_TEXT"];?>"
                                                ><?=$postData["PREVIEW_TEXT"];?></textarea>
                                                <div class="news-statistics-save-preview-text-buttons-wrapper">
                                                    <div
                                                        data-post-id="<?=$postData["ID"];?>"
                                                        id="news-statistics-save-preview-text-<?=$postData["ID"];?>"
                                                        class="news-statistics-save-preview-text <?=trim($postData["PREVIEW_TEXT"]) == "" ? "news-statistics-save-preview-text-disabled" : "";?>"
                                                    >Сохранить</div>
                                                    <div
                                                        id="news-statistics-save-preview-text-success-<?=$postData["ID"];?>"
                                                        class="news-statistics-save-preview-text-success">&#10004;
                                                    </div>
                                                    <div
                                                        id="news-statistics-save-preview-text-error-<?=$postData["ID"];?>"
                                                        class="news-statistics-save-preview-text-error">Ошибка сохранения
                                                    </div>
                                                </div>
                                            </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if (trim($arPostPieces) != "" && intVal($arPostPieces) != 0): ?>
                        <ul class="news-statistics-set-post-preview-text-pagination-list">
                            <?php for ($i = 1; $i <= intVal($arPostPieces); $i++): ?>
                                <li
                                    data-post-list="<?=$i;?>"
                                    class="news-statistics-set-post-preview-text-pagination-point<?=$i == 1 ? " news-statistics-set-post-preview-text-pagination-point-active" : ""; ?>">
                                    <?=$i;?>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    <?php endif;?>
                <?php endif; ?>
            </div>
        </div>
        <div class="news-statistics-user-data-wrapper">
            <div class="news-statistics-aside-title"><?=Loc::getMessage("USER_INFO");?></div>
            <div class="news-statistics-user-data-list-wrapper">
                <ul class="news-statistics-user-data-list">
                    <?php if (trim($arResult["CURRENT_USER"]["LAST_NAME"]) != "" || trim($arResult["CURRENT_USER"]["NAME"]) != ""): ?>
                    <?=trim($arResult["CURRENT_USER"]["LAST_NAME"]) != "" ? "<li>" . trim($arResult["CURRENT_USER"]["LAST_NAME"]) : "<li>";?>
                    <?=trim($arResult["CURRENT_USER"]["NAME"]) != "" ? trim($arResult["CURRENT_USER"]["NAME"]) . "</li>" : "</li>";?>
                    <?php endif; ?>
                    <?=trim($arResult["CURRENT_USER"]["LOGIN"]) != "" ? "<li>Логин: <span>" . trim($arResult["CURRENT_USER"]["LOGIN"]) . "</span></li>" : "";?>
                    <?=trim($arResult["CURRENT_USER"]["EMAIL"]) != "" ? " <li>Email: <span>" . trim($arResult["CURRENT_USER"]["EMAIL"]) . "</span></li>" : "";?>
                </ul>
            </div>
            <div id="news-statistics-change-user" class="news-statistics-change-user"><?=Loc::getMessage("CHANGE_USER");?></div>
            <div class="news-statistics-users-list-wrapper">
            <?php if (count($arResult["USERS_LIST"]) > 0): ?>
                <?php foreach ($arResult["USERS_LIST"] as $userID => $userData): ?>
                <div class="news-statistics-user-point" data-user-id="<?=$userID;?>" data-user-name="<?=$userData;?>"></div>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>
            <?if (count($arResult["PUBLISH_STATISTICS_POSTS"]) > 0): ?>
            <div class="news-statistics-post-diagramms-wrapper">
                <div id="news-statistics-post-diagramms-title" class="news-statistics-post-diagramms-title"><?=Loc::getMessage("POSTS_PER_MONTHS");?></div>
                <ul class="news-statistics-post-diagramms-select-year">
                    <?php foreach ($arResult["PUBLISH_STATISTICS_POSTS"] as $publishYear => $publishYearValue): ?>
                    <li data-year="<?=$publishYear?>"><?=$publishYear;?>
                        <?php if (count($publishYearValue) > 0): ?>
                        <?php $publishYearValue = array_reverse($publishYearValue, true); ?>
                        <ul class="news-statistics-post-diagramms-select-month">
                        <?php foreach ($publishYearValue as $publishMonth => $publishMonthValues): ?>
                            <li
                                class="news-statistics-post-diagramms-select-month-point"
                                data-year="<?=$publishYear;?>"
                                data-month="<?=$publishMonth;?>"
                                data-count="<?=count($publishMonthValues);?>">
                                <?=$arResult["RUS_MONTHS"][$publishMonth];?>:
                                <i><?=count($publishMonthValues);?> <?=CompanyNewsStatistics::getEndWords(intval(count($publishMonthValues)));?>
                                </i>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                        <div id="diagramm-year-<?=$publishYear;?>" class="diagramm-year" data-year="<?=$publishYear;?>"></div>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div id
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>