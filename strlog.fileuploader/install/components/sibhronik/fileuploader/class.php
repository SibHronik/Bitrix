<?php
namespace Sibhronik\Classes;

class CSV
{
    private $csvArray = [];
    private $csvKeys = [];
    private $csvValues = [];
    
    public static function csvIter($csvArr)
    {
        $csv = array_map('str_getcsv', file($csvArr));
        if (is_array($csv[0])) {
            foreach ($csv[0] as $key => $value) {
                $explodeValue = explode(';', $value);
                $csvKeys = $explodeValue;
            }
        } else {
            $csvKeys = $csv[0];
        }
        array_shift($csv);
        foreach ($csv as $key => $value) {
            $csvValues = explode(';', $value[0]);
            $csvArray[] = array_combine($csvKeys, $csvValues);
        }
        foreach ($csvArray as $key => $value) {
            $csvArray = $value;
            echo '<pre>';
            print_r($csvArray);
            echo '</pre>';
        }
    }
}

class XML
{
    private $xmlArray = [];
    public static function xmlIter ($xmlArr)
    {
        $xmlLoad = simplexml_load_file($xmlArr);
        $xml = json_decode(json_encode($xmlLoad));
        function xmlParse($arg){
            foreach ($arg as $key => $value) {
                if (is_array($value)) {
                    xmlParse($value);
                } else {
                    foreach ($value as $k => $v) {
                        $xmlArray[$k] = $v;
                    }
                }
				echo '<pre>';
				print_r($xmlArray);
				echo '</pre>';
            }
        }
        xmlParse($xml);
    }
}

class JSON
{
    private $jsonArray = [];
    public static function jsonIter ($jsonArr)
    {
        foreach ($jsonArr as $key => $value) {
            if (is_array($value)) {
                self::jsonIter($value);
            } else {
                $jsonArray[$key] = $value;
            }
        }
		echo '<pre>';
		print_r($jsonArray);
		echo '</pre>';
    }
}

