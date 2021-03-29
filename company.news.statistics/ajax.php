<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?php
class CompanyNewsStatisticsActions extends \Bitrix\Main\Engine\Controller
{
    private function checkErrors($arParams = [], $result)
    {
        if (count($arParams) > 0) {
            $errors = [];
            foreach ($arParams as $paramKey => $paramValue) {
                if (!isset($_POST[$paramKey]) && empty($_POST[$paramKey])) {
                    $errors[] = $paramValue;
                }
            }
            if (count($errors) > 0) {
                $result->setData(["errors" => $errors]);
                return $result->getData();
            }
        }
    }

    private function clearCache($userID)
    {
        Bitrix\Main\Data\Cache::clearCache(true, "statistics/user/" . $userID);
    }

    public function DeleteTagAction()
    {
        \Bitrix\Main\Loader::includeModule("blog");

        $result = new \Bitrix\Main\Result;

        $arParams = [
            "TAG_ID" => "TAG ID is empty",
            "CURRENT_USER_ID" => "USER ID is empty"
        ];
        $errors = $this -> checkErrors($arParams, $result);

        if (count($errors) > 0) {
            return $result -> getData();
        }

        if ($result -> isSuccess()) {
            $tagID = htmlspecialcharsEx(trim($_POST["TAG_ID"]));
            if (!CBlogCategory::Delete($tagID)) {
                $result->setData(["errors" => "Не удалось удалить тэг"]);
                return  $result -> getData();
            } else {
                $userID = htmlspecialcharsEx(trim($_POST["CURRENT_USER_ID"]));
                $this -> clearCache($userID);
            }
        }
        return $result;
    }

    public function UpdatePostDataAction()
    {
        \Bitrix\Main\Loader::includeModule("blog");

        $result = new \Bitrix\Main\Result;

        $arParams = [
            "ID" => "POST ID is empty",
            "CURRENT_USER_ID" => "USER ID is empty"
        ];
        $errors = $this -> checkErrors($arParams, $result);
        if (count($errors) > 0) {
            return $result -> getData();
        }

        if ($result -> isSuccess()) {
            $userID = htmlspecialcharsEx(trim($_POST["CURRENT_USER_ID"]));
            $this -> clearCache($userID);
            $postID = htmlspecialcharsEx(trim($_POST["ID"]));
            $postTitle = htmlspecialcharsEx(trim($_POST["TITLE"]));
            $postPreviewText = htmlspecialcharsEx(trim($_POST["PREVIEW_TEXT"]));
            if (count($_POST["CATEGORY_ID"]) > 0) {
                foreach ($_POST["CATEGORY_ID"] as $categoryID) {
                    $categoryIDs .= trim($categoryIDs == "") ? $categoryID : "," . $categoryID;
                }
            }
            $updatePostID = CBlogPost::Update($postID, ["TITLE" => $postTitle, "PREVIEW_TEXT" => $postPreviewText, "CATEGORY_ID" => $categoryIDs]);
            $result -> setData(["POST_ID" => $updatePostID]);
        }
        return $result -> getData();
    }
}
