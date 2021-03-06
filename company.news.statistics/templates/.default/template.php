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
                            <span class="news-statistics-tag-delete"
                                data-user-id="<?=$arResult['CURRENT_USER']['ID'];?>"
                                data-tag-id="<?=$tagValue["ID"];?>"
                                data-tag-name="<?=$tagValue["NAME"];?>">
                                <?=Loc::getMessage("DELETE_TAG_TITLE");?>
                            </span>
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
                <div class="news-statistics-set-post-preview-text-title"><?=Loc::getMessage("POST_LIST");?></div>
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
                                        <span class="news-statistics-set-post-preview-text-date"> <?=Loc::getMessage("PUBLIC");?> <?=$postData["DATE_CREATE"];?></span>
                                        <div class="news-statistics-set-post-title-and-preview-text-dropdown">
                                            <span class="set-post-data-dropdown-button" data-post-id="<?=$postData["ID"];?>">
                                                <?=Loc::getMessage("SET_TITLE_AND_PREVIEW_TEXT");?>
                                            </span>
                                        </div>

                                        <div
                                            id="news-statistics-set-post-data-wrapper-<?=$postData["ID"];?>"
                                            class="news-statistics-set-post-data-wrapper"
                                        >
                                            <input type="text" id="news-statistics-set-title-<?=$postData["ID"];?>"
                                               class="news-statistics-set-title"
                                               placeholder="<?=Loc::getMessage("SET_TITLE");?>"
                                               data-post-id="<?=$postData["ID"];?>"
                                               data-length="<?=Loc::getMessage("TITLE_LENGTH");?>"
                                               value="<?=$postData["TITLE"];?>"
                                            />
                                            <div class="news-statistics-set-title-symbols-quantity-wrapper">
                                                <?=Loc::getMessage("SYMBOLS_RECOMMENDED");?>
                                                <span class="news-statistics-set-title-symbols-quantity"><?=Loc::getMessage("TITLE_LENGTH");?></span>.
                                                <?=Loc::getMessage("SYMBOLS_INPUT");?>
                                                <!--sq-symbols-quantity-->
                                                <span
                                                    id="set-title-sq-passed-<?=$postData['ID'];?>"
                                                    class="set-title-sq-passed<?=intVal(mb_strlen($postData['TITLE'])) > intVal(Loc::getMessage("TITLE_LENGTH")) ? " set-title-sq-danger" : ""; ?>"
                                                ><?=mb_strlen($postData["TITLE"]);?></span>
                                            </div>
                                            <textarea data-post-id="<?=$postData["ID"];?>"
                                                  id="news-statistics-set-preview-text-area-<?=$postData["ID"];?>"
                                                  class="news-statistics-set-preview-text-area"
                                                  data-length="<?=Loc::getMessage("PREVIEW_TEXT_LENGTH");?>"
                                                  value="<?=$postData["PREVIEW_TEXT"];?>"
                                            ><?=$postData["PREVIEW_TEXT"];?></textarea>
                                            <div class="news-statistics-set-preview-text-symbols-quantity-wrapper">
                                                <?=Loc::getMessage("SYMBOLS_RECOMMENDED");?>
                                                <span class="news-statistics-set-preview-text-symbols-quantity"><?=Loc::getMessage("PREVIEW_TEXT_LENGTH");?></span>.
                                                <?=Loc::getMessage("SYMBOLS_INPUT");?>
                                                <!--sq-symbols-quantity-->
                                                <span
                                                    id="set-preview-text-sq-passed-<?=$postData['ID'];?>"
                                                    class="set-preview-text-sq-passed<?=intVal(mb_strlen($postData['PREVIEW_TEXT'])) > intVal(Loc::getMessage("PREVIEW_TEXT_LENGTH")) ? " set-preview-text-sq-danger" : ""; ?>">
                                                    <?=mb_strlen($postData["PREVIEW_TEXT"]);?>
                                                </span>
                                            </div>
                                            <?php if (count($arResult["TAGS_VALUES"]) > 0): ?>
                                            <div class="news-statistics-post-tags-wrapper">
                                                <?php $postTags = []; ?>
                                                <?php if (trim($postData["CATEGORY_ID"]) != ""): ?>
                                                <?php $postTags = explode(",", $postData["CATEGORY_ID"]); ?>
                                                <?php endif; ?>
                                                <div id="news-statistics-post-tags-title-<?=$postData['ID'];?>"
                                                     class="news-statistics-post-tags-title"><?=Loc::getMessage("TAGS");?> </div>
                                                <ul id="news-statistics-post-tags-list-wrapper-<?=$postData['ID'];?>"
                                                    class="news-statistics-post-tags-list-wrapper">
                                                <?php if (count($postTags) > 0): ?>
                                                    <?php foreach ($postTags as $postTag): ?>
                                                    <?php if (in_array($postTag, array_keys($arResult["TAGS_VALUES"]))): ?>
                                                    <li class="news-statistics-post-tag"
                                                        data-tag-id="<?=$postTag;?>"
                                                        data-post-id="<?=$postData['ID'];?>"><?=$arResult["TAGS_VALUES"][$postTag];?></li>
                                                    <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                </ul>
                                                    <div id="news-statistics-post-tags-title-<?=$postData['ID'];?>"
                                                         class="news-statistics-post-tags-title"><?=Loc::getMessage("TAGS_NOT_FOUND");?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="news-statistics-exist-post-tags-wrapper">
                                                <div class="news-statistics-exist-post-tags-title"><?=Loc::getMessage("AVAILABLE_TAGS");?></div>
                                                <ul id="news-statistics-exist-post-tags-list-wrapper-<?=$postData['ID'];?>"
                                                    class="news-statistics-exist-post-tags-list-wrapper">
                                                <?php foreach ($arResult["TAGS_VALUES"] as $postExistTagKey => $postExitstTagValue): ?>
                                                    <li class="news-statistics-exist-post-tag<?=in_array($postExistTagKey, $postTags) ? ' news-statistics-exist-post-tag-active' : '';?>"
                                                        data-tag-id="<?=$postExistTagKey;?>"
                                                        data-post-id="<?=$postData['ID'];?>"><?=$postExitstTagValue;?></li>
                                                <?php endforeach; ?>
                                                </ul>
                                            </div>
                                            <?php endif; ?>
                                            <div class="news-statistics-save-post-data-buttons-wrapper">
                                                <div class="news-statistics-save-post-data-buttons">
                                                    <div
                                                        data-post-id="<?=$postData["ID"];?>"
                                                        data-user-id="<?=$arResult['CURRENT_USER']['ID'];?>"
                                                        id="news-statistics-save-post-data-<?=$postData['ID'];?>"
                                                        class="news-statistics-save-post-data<?=trim($postData["PREVIEW_TEXT"]) == "" ? " news-statistics-save-post-data-disabled" : "";?>"
                                                    >Сохранить</div>
                                                    <div
                                                        id="news-statistics-save-post-data-success-<?=$postData['ID'];?>"
                                                        class="news-statistics-save-post-data-success">&#10004;
                                                    </div>
                                                    <div
                                                        id="news-statistics-save-post-data-error-<?=$postData['ID'];?>"
                                                        class="news-statistics-save-post-data-error"><?=Loc::getMessage("SAVE_ERROR");?>
                                                    </div>
                                                </div>
                                                <div class="news-statistics-open-preview-button"
                                                    data-post-id="<?=$postData['ID'];?>"><?=Loc::getMessage("OPEN_PREVIEW");?></div>
                                                <div id="news-statistics-preview-posts-wrapper-<?=$postData['ID'];?>"
                                                    class="news-statistics-preview-posts-wrapper"
                                                    data-post-id="<?=$postData['ID'];?>">
                                                    <div class="news-statistics-preview-posts-triangle"></div>
                                                    <div class="news-statistics-preview-posts">
                                                        <div class="news-statistics-preview-post-left-wrapper">
                                                            <div class="news-statistics-preview-post-image"></div>
                                                        </div>
                                                        <div id="news-statistics-preview-post-center-wrapper-<?=$postData['ID'];?>"
                                                            class="news-statistics-preview-post-center-wrapper">
                                                            <div class="news-statistics-preview-post-image">
                                                                <div class="news-statistics-preview-post-image-title"><?=Loc::getMessage("IMAGE");?></div>
                                                            </div>
                                                            <div id="news-statistics-preview-post-title-<?=$postData['ID'];?>"
                                                                class="news-statistics-preview-post-title"><?=trim($postData["TITLE"]);?></div>
                                                            <div  id="news-statistics-preview-post-description-<?=$postData['ID'];?>"
                                                                class="news-statistics-preview-post-description"><?=trim($postData["PREVIEW_TEXT"]);?></div>
                                                            <div class="news-statistics-preview-post-tags-wrapper">
                                                                <ul id="news-statistics-preview-post-tags-<?=$postData['ID'];?>"
                                                                    class="news-statistics-preview-post-tags">
                                                                <?php if (count($postTags) > 0): ?>
                                                                    <li>Тэги: </li>
                                                                    <?php foreach ($postTags as $postTag): ?>
                                                                    <li id="news-statistics-preview-post-tag-<?=$postTag;?>"><?=$arResult["TAGS_VALUES"][$postTag];?></li>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                            <div class="news-statistics-preview-post-date-like-wrapper">
                                                                <div class="news-statistics-preview-post-date">
                                                                    <?=date("d.m.Y", strtotime($postData["DATE_CREATE"]));?>
                                                                </div>
                                                                <div class="news-statistics-preview-post-likes">
                                                                    <?=$postData["VOTES"];?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="news-statistics-preview-post-right-wrapper">
                                                            <div class="news-statistics-preview-post-image"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if (trim($arPostPieces) != "" && intVal($arPostPieces) > 1): ?>
                        <ul class="news-statistics-pagination-list">
                            <?php for ($i = 1; $i <= intVal($arPostPieces); $i++): ?>
                                <li
                                    data-post-list="<?=$i;?>"
                                    class="news-statistics-pagination-point<?=$i == 1 ? " news-statistics-pagination-point-active" : ""; ?>">
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