<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses('strlog.userupdate', array(
	'Strlog\Userupdate\Classes\Merger' => 'classes/Merger.php',
	'Strlog\Userupdate\Classes\CheckUsers' => 'classes/Check.php',
	'Strlog\Userupdate\Classes\HLFields' => 'classes/HLFields.php',
	'Strlog\Userupdate\Classes\UserFields' => 'classes/UserFields.php',
));
