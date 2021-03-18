<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?php
class CompanyNewsStatisticsActions extends \Bitrix\Main\Engine\Controller
{
    public function DeleteTagAction()
    {
        $result = new \Bitrix\Main\Result;
        \Bitrix\Main\Loader::includeModule("blog");
        if (!isset($_POST["TAG_ID"]) && empty($_POST["TAG_ID"])) {
            $result -> addError("TAG ID is empty");
        }

        if ($result -> isSuccess()) {
            $tagID = htmlspecialcharsEx(trim($_POST["TAG_ID"]));
            if (!CBlogCategory::Delete($tagID)) {
                $result -> addError("Error");
            }
        }
        return $result;
    }

    public function UpdatePostPreviewTextAction()
    {
        $result = new \Bitrix\Main\Result;
        \Bitrix\Main\Loader::includeModule("blog");
        if (!isset($_POST["POST_ID"]) && empty($_POST["POST_ID"])) {
            $result -> addError("POST ID is empty");
        }

        if ($result -> isSuccess()) {
            $postID = htmlspecialcharsEx(trim($_POST["POST_ID"]));
            $postPreviewText = htmlspecialcharsEx(trim($_POST["POST_PREVIEW_TEXT"]));
            $updatePostID = CBlogPost::Update($postID, ["PREVIEW_TEXT" => $postPreviewText]);
            $result -> setData(["POST_ID" => $updatePostID]);
        }
        return $result;
    }
}
