<?php
namespace Closest\Number\Controller;

use Bitrix\Main\Engine\Controller;

class Search extends Controller
{
    public static function chunkAction($arr = [], $num = 0, $side = false)
    {
        /**
        * $arr - массив чисел в котором ищем ближайшее число
        * $num - число
        * $side - в случае если два числа являются равнозначно удаленными числами к искомому, то всегда берется большее число
        * и если $side установить в true, то будет возвращаться меньшее число
        * Массив для примера $arr = [-60, -50, -40, -30, -20, -10, 50, 60, 70, 90];
        */

        if (!$_REQUEST["ARR"] || trim($_REQUEST["ARR"]) == "") return ["ERROR" => "Не введены числа масива"];
        if (!$_REQUEST["NUM"] || trim($_REQUEST["NUM"]) == "") return ["ERROR" => "Не введено число"];
        if (!is_numeric($_REQUEST["NUM"])) return ["ERROR" => "Искомое не является числом"];

        if (!$arr) {
            $arr = str_ireplace(" ", "", $_REQUEST["ARR"]);
            $arr = explode(",", $arr);
        }
        sort($arr); // На всякий случай еще раз отсортируем сами
        $num = intval($_REQUEST["NUM"]);
        $side = $_REQUEST["SIDE"]  && $_REQUEST["SIDE"] == "true" ? true : false;
        if (!is_int($num)) return "Не число"; // Проверяем, что входящее число - это число
        if (count($arr) < 1) return "Пустой массив"; // Проверяем, что массив не пустой
        if (count($arr) > 0 && count($arr) < 2) return $arr[0]; // Если остался один элемент, то его значение и есть искомое число

        // Определим размер разбиваемых массивов с округлением в большую сторону
        $countHalf = ceil(count($arr) / 2);

        // Так как массив упорядоченный, то можем разбить его на две части
        // И сравнить разницу со входящим числом у последнего элемента массива слева
        // с разницей со входящим числом у первого элемента массива справа
        $chunkArrs = array_chunk($arr, $countHalf);
        $leftNum = abs(intval(end($chunkArrs[0]) - $num)); //Последнее число первого массива минус входящее число
        $rightNum = abs(intval($chunkArrs[1][0] - $num)); //Первое число второго массива минус входящее число

        // Если встречаем с двух сторон равное удаление от чисел, то если $side === false берем большее число. Если true, то меньшее
        $newArr = $side === false ?
            $leftNum < $rightNum ? $chunkArrs[0] : $chunkArrs[1] :
            $newArr = $leftNum <= $rightNum ? $chunkArrs[0] : $chunkArrs[1];

        //return Search::testAction(1);
        return Search::chunkAction($newArr, $num, $side);
    }

    public static function reduceAction()
    {
        /**
        * $arr - массив чисел в котором ищем ближайшее число
        * $num - число
        * Массив для примера $arr = [-60, -50, -40, -30, -20, -10, 50, 60, 70, 90];
        */
        if (!$_REQUEST["ARR"] || trim($_REQUEST["ARR"]) == "") return "Не введены числа масива";
        if (!$_REQUEST["NUM"] || trim($_REQUEST["NUM"]) == "") return "Не введено число";
        if (!is_int(intval($_REQUEST["NUM"]))) return "Искомое не является числом";
        $arr = str_ireplace(" ", "", $_REQUEST["ARR"]);
        $arr = explode(",", $arr);
        $num = intval($_REQUEST["NUM"]);

        if (!is_int($num)) return "Не число"; // Проверяем, что входящее число - это число
        if (count($arr) < 1) return "Пустой массив"; // Проверяем, что массив не пустой
        if (count($arr) > 0 && count($arr) < 2) return $arr[0]; // Если один элемент, то его значение и есть искомое число

        $start = abs($arr[0] - $num); //Устанавливаем первую проверку равную разнице числа и первым элементом массива
        $result = [];

        array_reduce($arr, function ($carry, $item) use (&$result, $start, $num) {
            $result[abs($item - $num)] = $item;
            return abs($item - $num) < $carry ? abs($item - $num) : $carry;
        }, $start);
        krsort($result); // Сортируем для удобства

        return end($result); // Последний элемент после сортировки является наименьшей разницей между числом и значением массива
    }

    public static function iterAction()
    {
        /**
        * $arr - массив чисел в котором ищем ближайшее число
        * $num - число
        * Массив для примера $arr = [-60, -50, -40, -30, -20, -10, 50, 60, 70, 90];
        */

        if (!$_REQUEST["ARR"] || trim($_REQUEST["ARR"]) == "") return "Не введены числа масива";
        if (!$_REQUEST["NUM"] || trim($_REQUEST["NUM"]) == "") return "Не введено число";
        if (!is_int(intval($_REQUEST["NUM"]))) return "Искомое не является числом";
        $arr = str_ireplace(" ", "", $_REQUEST["ARR"]);
        $arr = explode(",", $arr);
        $num = intval($_REQUEST["NUM"]);

        if (!is_int($num)) return "Не число"; // Проверяем, что входящее число - это число
        if (count($arr) < 1) return "Пустой массив"; // Проверяем, что массив не пустой
        if (count($arr) > 0 && count($arr) < 2) return $arr[0]; // Если остался один элемент, то его значение и есть искомое число

        $result = [];
        foreach ($arr as $key => $value) {
            $result[$value] = abs($value - $num);
        }
        $minKey = min($result);
        $result = array_flip($result);
        return $result[$minKey];
    }
}