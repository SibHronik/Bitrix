<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;

class ClosestNumber extends CBitrixComponent
{
    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent()
    {
        try {
            $this -> includeComponentTemplate();
        } catch (Exception $error) {
            global $USER;
            if ($USER->IsAdmin()) {
                print_r($error -> getMessage());
            }
        }
    }
}