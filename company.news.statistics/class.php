<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CompanyNewsStatistics extends CBitrixComponent
{
    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public static function getEndWords($number, $words = ["пост", "поста", "постов"])
    {
        $number = $number % 100;
        if ($number > 19) {
            $number = $number % 10;
        }
        switch ($number) {
            case 1: {
                return($words[0]);
            }
            case 2: case 3: case 4: {
                return($words[1]);
            }
            default: {
                return($words[2]);
            }
        }
    }

    private function getUserData()
    {
        if (empty($this -> arParams["USERS"])) {
            $result["ERRORS"] = ["ERROR_MESSAGE" => "Не выбран ни один пользователь"];
            return $result;
        }
        $queryUser = \Bitrix\Main\UserTable::getList([
            "filter" => ["ID" => $this -> arParams["USERS"]],
            "select" => ["ID", "ACTIVE", "NAME", "LAST_NAME", "LOGIN", "EMAIL"]
        ]);
        while ($user = $queryUser -> fetch()) {
            $result = $user;
        }
        return $result;
    }

    private function getRusMonths()
    {
        //Может быть не установлена локаль, поэтому используем массив
        $months = [
            "01" => "Январь",
            "02" => "Февраль",
            "03" => "Март",
            "04" => "Апрель",
            "05" => "Май",
            "06" => "Июнь",
            "07" => "Июль",
            "08" => "Август",
            "09" => "Сентябрь",
            "10" => "Отябрь",
            "11" => "Ноябрь",
            "12" => "Декабрь",
        ];
        return $months;
    }

    private function getBlogPosts($userID = 0)
    {
        $result = [];
        $result["CURRENT_USER"] = self::getUserData();
        $userID = intval($userID) == 0 ? $this -> arParams["USERS"] : $userID;
        $queryBlog = CBlog::GetByOwnerID($userID);
        $blogID = $queryBlog["ID"];
        $result["CURRENT_USER"]["BLOG_ID"] = $blogID;
        $parser = new CTextParser;
        $blogPosts = [];
        $topComments = []; //Кол-во комментариев
        $topVotes = []; //Кол-во лайков
        $topViews = []; //Кол-во просмотров
        $topCommentators = []; //Список комментаторов
        $publishStatistics = []; //Статистика публикаций

        /*CACHE START*/
        $cacheTime = 3600;
        $cacheId = "statisticsPosts" . $result["CURRENT_USER"]["ID"];
        $cacheDir = "statistics/user/" . $result["CURRENT_USER"]["ID"];
        $cache = Bitrix\Main\Data\Cache::createInstance();
        if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
            $result = $cache->getVars();
            $result = $result["RESULT"];
        } elseif ($cache->startDataCache()) {
            $blogPostOrder = ["ID" => "DESC"];
            $blogPostFilter = [
                "BLOG_ID" => $blogID,
                "AUTHOR_ID" => $this->arParams["USERS"],
                "PUBLISH_STATUS" => "P"
            ];
            $blogPostSelect = [
                "ID",
                "AUTHOR_ID",
                "TITLE",
                "DATE_CREATE",
                "DATE_PUBLISH",
                "NUM_COMMENTS",
                "NUM_COMMENTS_ALL",
                "CATEGORY_ID",
                "VIEWS",
                "PREVIEW_TEXT"
            ];
            $queryBlogPosts = CBlogPost::GetList($blogPostOrder, $blogPostFilter, false, false, $blogPostSelect);
            while ($getBlogPost = $queryBlogPosts->fetch()) {
                $querySocNetPermissions = CBlogPost::GetSocNetPostPerms($getBlogPost["ID"], false, $this->arParams["USERS"]);
                if (trim($querySocNetPermissions) != "D") {
                    $arBlogPost[$getBlogPost["ID"]] = $getBlogPost;
                }
            }
            foreach ($arBlogPost as $blogPostID => $blogPost) {
                $blogPost["TITLE"] = trim($blogPost["TITLE"]) == "" ? "Без названия" . " [" . $blogPost["ID"] . "]" : trim($blogPost["TITLE"]);
                $blogPost["PREVIEW_TEXT"] = trim($blogPost["PREVIEW_TEXT"]) == "" ? "" : $blogPost["PREVIEW_TEXT"];
                $queryComments = CBlogComment::GetList(
                    ["ID" => "DESC"],
                    ["BLOG_ID" => $blogID, "POST_ID" => $blogPost["ID"]], false, false,
                    ["ID", "AUTHOR_ID", "POST_TEXT"]
                );
                while ($comment = $queryComments->fetch()) {
                    if (trim($comment["AUTHOR_ID"]) != "") {
                        $queryUser = CUser::GetByID($comment["AUTHOR_ID"])->fetch();
                        if ($queryUser) {
                            $topCommentators[$comment["AUTHOR_ID"]]["AUTHOR_ID"] = $blogPost["AUTHOR_ID"];
                            $topCommentators[$comment["AUTHOR_ID"]]["USER"] = $queryUser["LAST_NAME"] . " " . $queryUser["NAME"]; //Комментатор постов
                            $topCommentators[$comment["AUTHOR_ID"]]["COUNT"] = //Подсчитываем сколько всего постов откомментировал пользователь
                                isset($topCommentators[$comment["AUTHOR_ID"]]["COUNT"]) ?
                                    $topCommentators[$comment["AUTHOR_ID"]]["COUNT"] += 1 : 1;
                            $topCommentators[$blogPost["ID"]][$comment["AUTHOR_ID"]] = //Подсчитываем сколько раз пользователь откомментировал каждый пост
                                isset($topCommentators[$blogPost["ID"]][$comment["AUTHOR_ID"]]) ?
                                    $topCommentators[$blogPost["ID"]][$comment["AUTHOR_ID"]] += 1 : 1;
                            $topCommentators[$comment["AUTHOR_ID"]]["POSTS"][$comment["AUTHOR_ID"] . $blogPost["ID"]]["POST_ID"] = $blogPost["ID"];
                            $topCommentators[$comment["AUTHOR_ID"]]["POSTS"][$comment["AUTHOR_ID"] . $blogPost["ID"]]["POST_NAME"] = $blogPost["TITLE"]; //Название поста + кол-во комментариев пользователя
                            $topCommentators[$comment["AUTHOR_ID"]]["POSTS"][$comment["AUTHOR_ID"] . $blogPost["ID"]]["USER_POST_COMMENTS"][$comment["ID"]] = $parser->convertText($comment["POST_TEXT"]); //Конвертируем BB code текста комментария
                            //Удаляем изображения
                            preg_match_all('#\[DISK FILE ID=(.*?)\]#', $comment["POST_TEXT"], $commentImages);
                            foreach ($commentImages[0] as $commentImageKey => $commentImageValue) {
                                if (trim($commentImageValue) != "") $topCommentators[$comment["AUTHOR_ID"]]["POSTS"][$comment["AUTHOR_ID"] . $blogPost["ID"]]["USER_POST_COMMENTS"][$comment["ID"]] = str_ireplace($commentImageValue, "", $topCommentators[$comment["AUTHOR_ID"]]["POSTS"][$blogPost["ID"]]["USER_POST_COMMENTS"][$comment["ID"]]);
                            }
                        }
                    }
                }
                $topComments[$blogPost["ID"]]["NAME"] = $blogPost["TITLE"];
                $topComments[$blogPost["ID"]]["TOTAL_COMMENTS"] = $blogPost["NUM_COMMENTS"];

                $topViews[$blogPost["ID"]]["NAME"] = $blogPost["TITLE"];
                $topViews[$blogPost["ID"]]["TOTAL_VIEWS"] = $blogPost["VIEWS"];

                $queryVotes = CRatings::GetRatingVoteResult("BLOG_POST", $blogPost["ID"]);
                $topVotes[$blogPost["ID"]]["NAME"] = $blogPost["TITLE"];
                if (intval($queryVotes["TOTAL_VOTES"]) > 0) {
                    $topVotes[$blogPost["ID"]]["TOTAL_VOTES"] = $queryVotes["TOTAL_VOTES"];
                    foreach ($queryVotes["USER_VOTE_LIST"] as $userVoteID => $userVoteValue) {
                        $queryUser = CUser::GetByID($userVoteID)->fetch();
                        if ($queryUser) {
                            $topVotes[$blogPost["ID"]]["USERS"][$queryUser["ID"]] = $queryUser["LAST_NAME"] . " " . $queryUser["NAME"];
                        }
                    }
                } else {
                    $topVotes[$blogPost["ID"]]["TOTAL_VOTES"] = "0";
                }
                $blogPosts[] = $blogPost;

                $yearCreate = date("Y", strtotime($blogPost["DATE_CREATE"]));
                $monthCreate = date("m", strtotime($blogPost["DATE_CREATE"]));
                $publishStatistics[$yearCreate][$monthCreate][$blogPost["ID"]] = $blogPost["DATE_CREATE"];
            }

            $result["POSTS"] = $blogPosts;
            $result["PUBLISH_STATISTICS_POSTS"] = $publishStatistics;

            $blogPostsTags = [];
            foreach ($blogPosts as $blogPostKey => $blogPostValue) {
                if (trim($blogPostValue["CATEGORY_ID"]) != "") {
                    $blogPostsTags[$blogPostValue["ID"]]["ID"] = explode(",", $blogPostValue["CATEGORY_ID"]);
                    $blogPostsTags[$blogPostValue["ID"]]["NAME"] = $blogPostValue["TITLE"] . " (" . date("d.m.Y H:i", strtotime($blogPostValue["DATE_CREATE"])) . ")";
                }
            }
            $queryCategories = CBlogCategory::GetList(["ID" => "DESC"], ["BLOG_ID" => $blogID, "AUTHOR_ID" => $this -> arParams["USERS"]], false, false, ["ID", "NAME"]);
            $tags = [];
            $tagsValues = [];
            while ($category = $queryCategories -> fetch()) {
                $tagsValues[$category["ID"]] = $category["NAME"];
                $tags[$category["ID"]] = $category;
                $tags[$category["ID"]]["TAG_COUNT"] = 0;
                foreach ($blogPostsTags as $blogPostTagKey => $blogPostTagValue) {
                    if (in_array($category["ID"], $blogPostTagValue["ID"])) {
                        $tags[$category["ID"]]["TAG_COUNT"] += 1;
                        $tags[$category["ID"]]["POSTS"][$blogPostTagKey] = $blogPostTagValue["NAME"];
                    }
                }
            }
            $result["TAGS_VALUES"] = $tagsValues;

            function sortTags ($b, $a)
            {
                return intVal(intVal($a["TAG_COUNT"]) > intVal($b["TAG_COUNT"]));
            }
            uasort($tags, "sortTags");
            $result["TAGS"] = $tags;

            function sortTopComments ($b, $a) //кол-во комментариев у поста
            {
                return intVal(intVal($a["TOTAL_COMMENTS"]) > intVal($b["TOTAL_COMMENTS"]));
            }
            uasort($topComments, "sortTopComments");
            if (count($topComments) > 10) {
                $topComments = array_slice($topComments, 0, 10, true);
            }
            if (count($topComments) > 0) {
                $result["TOP_COMMENTS"] = $topComments;
            }

            function sortVotes ($b, $a) //кол-во лайков у поста
            {
                return intVal(intVal($a["TOTAL_VOTES"]) > intVal($b["TOTAL_VOTES"]));
            }
            uasort($topVotes, "sortVotes");
            foreach ($topVotes as $postID => &$postValue) {
                asort($postValue["USERS"]);
            }
            if (count($topVotes) > 10) {
                $topVotes = array_slice($topVotes, 0, 10, true);
            }
            if (count($topVotes) > 0) {
                $result["TOP_VOTES"] = $topVotes;
            }

            function sortViews ($b, $a) //Кол-во просмотров
            {
                return intVal(intVal($a["TOTAL_VIEWS"]) > intVal($b["TOTAL_VIEWS"]));
            }
            uasort($topViews, "sortViews");
            if (count($topViews) > 10) {
                $topViews = array_slice($topViews, 0, 10, true);
            }
            if (count($topViews) > 0) {
                $result["TOP_VIEWS"] = $topViews;
            }

            function sortTopCommentators ($b, $a) //Топ комментаторов
            {
                return intVal(intVal($a["COUNT"]) > intVal($b["COUNT"]));
            }
            uasort($topCommentators, "sortTopCommentators");
            if (count($topCommentators) > 10) {
                $topCommentators = array_slice($topCommentators, 0, 10, true);
            }
            if (count($topCommentators) > 0) {
                $result["TOP_COMMENTATORS"] = $topCommentators;
            }
            if (count($result["POSTS"]) < 1) {
                $result = [];
                $result["ERRORS"] = ["ERROR_MESSAGE" => "Посты не найдены"];
            }
            $cache->endDataCache(["RESULT" => $result]);
        }

        return $result;
    }

    public function executeComponent()
    {
        \Bitrix\Main\Loader::includeModule("blog");
        $this -> arResult = self::getBlogPosts();
        $this -> arResult["RUS_MONTHS"] = self::getRusMonths();
        $this -> arResult["COMPONENT_PATH"] = $this -> GetPath();

        $cacheTime = 86400;
        $cacheId = "statisticsID";
        $cacheDir = "statistics";
        $cache = Bitrix\Main\Data\Cache::createInstance();
        if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
            $users = $cache->getVars();
            $users = $users["USERS"];
            $this -> arResult["USERS_LIST"] = $users;
        }
        if (!isset($users) || count($users) < 1 || empty($users)) {
            if ($cache->startDataCache()) {
                $queryUser = \Bitrix\Main\UserTable::getList([
                    "filter" => ["ACTIVE" => "Y"],
                    "select" => ["ID", "NAME", "LAST_NAME", "LOGIN"],
                ]);
                $users = [];
                while ($user = $queryUser->fetch()) {
                    $users[$user["ID"]] = $user["LAST_NAME"] . " " . $user["NAME"] . " [" . $user["LOGIN"] . "]";
                }
                asort($users);
                $this -> arResult["USERS_LIST"] = $users;
                $cache->endDataCache(["USERS" => $users]);
            }
        }

        try {
            $this -> includeComponentTemplate();
        } catch (Exception $error) {
            global $USER;
            if ($USER->IsAdmin()) {
                print_r($error->getMessage());
            }
        }
    }
}