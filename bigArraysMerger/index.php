<?php
namespace Sibhronik;
include('class.php');

use Sibhronik\ArraysMerger\ArraysMergerClass as ArrsMerger;

/*
$firstArray = [];
for($i = 0; $i <= 1000; $i++){
    $firstArray["Element-$i"] = array("NAME" => "Element-$i", "BONUSES" => "0", "EX_1" => "EX_$i");
}

$secondArray = [];
for($i = 0; $i <= 100; $i++){
    $secondArray["Element-$i"] = array("NAME" => "Element-$i", "BONUSES" => 10+$i, "EX_2" => "EX_$i");
}
*/

$firstArray = array(
    'John' => array(
        'NAME' => 'John',
        'BONUSES' => '100',
    ),
    'Jane' => array(
        'NAME' => 'Jane',
        'BONUSES' => '110',
    ),
    'Jill' => array(
        'NAME' => 'Jill',
        'BONUSES' => '120',
    ),
);

$secondArray = array(
    'Jill' => array(
        'NAME' => 'Jill',
        'BONUSES' => '50',
    ),
    'Jason' => array(
        'NAME' => 'Jason',
        'BONUSES' => '10',
    ),
    'Jimmy' => array(
        'NAME' => 'Jimmy',
        'BONUSES' => '20',
    ),
    'John' => array(
        'NAME' => 'John',
        'BONUSES' => '30',
    ),
    'James' => array(
        'NAME' => 'James',
        'BONUSES' => '60',
    ),
    'June' => array(
        'NAME' => 'June',
        'BONUSES' => '70',
    ),
    'Jillian' => array(
        'NAME' => 'Jillian',
        'BONUSES' => '80',
    ),
    'Jane' => array(
        'NAME' => 'Jane',
        'BONUSES' => '40',
    ),
);

ArrsMerger::inputArrays($firstArray, $secondArray);







