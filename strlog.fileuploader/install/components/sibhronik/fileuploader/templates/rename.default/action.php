<?php
include('../../class.php');
use Sibhronik\Classes\CSV;
use Sibhronik\Classes\XML;
use Sibhronik\Classes\JSON;

if ($_FILES['file']['error'] > 0) {
    echo 'Ошибка загрузки файла №' . $_FILES['file']['error'];
} else {
    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $_FILES['file']['name']);
    $file = file_get_contents('uploads/' . $_FILES['file']['name']);
    $fileName = 'uploads/' . $_FILES['file']['name'];
    echo $filename;
    $extension = strrchr($_FILES['file']['name'], '.');
    switch ($extension) {
        case '.json':
            $jsonData = json_decode($file, true);
            JSON::jsonIter($jsonData);
            break;
        case '.xml':
            XML::xmlIter($fileName);
            break;
        case '.csv':
            CSV::csvIter($fileName);
            break;
        default:
            echo 'No extension file';
            break;
    }
}