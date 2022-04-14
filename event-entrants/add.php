<?php 

use Database\Database;
use Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

require_once(__DIR__ .'/../vendor/autoload.php');
require_once(__DIR__ .'/EventEntrants.php');

date_default_timezone_set( "Asia/Tokyo" );

$host = "http://localhost:4444/wd/hub";

global $driver, $moduleName;

$driver = Facebook\WebDriver\Remote\RemoteWebDriver::create($host, Facebook\WebDriver\Remote\DesiredCapabilities::chrome());

$moduleName = "event-entrants-add";

$url = "http://192.168.3.211:8006/event/nkb/App/Views/EventEntrants/add.php";

$driver->get($url);

$driver->manage()->window()->maximize();

checkValidatedFormJS();

function resetInputForm() {
    $GLOBALS['driver']->findElement(WebDriverBy::id("sral_p_1"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("sral_p_2"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("sral_p_3"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("sral_p_4"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("year"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("month"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("day"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("entrant_admin_comment"))->clear();
}
function autoInputForm($arrVal) {
    resetInputForm();
    $GLOBALS['driver']->findElement(WebDriverBy::id("sral_p_1"))->sendKeys($arrVal['sral_p_1']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("sral_p_2"))->sendKeys($arrVal['sral_p_2']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("sral_p_3"))->sendKeys($arrVal['sral_p_3']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("sral_p_4"))->sendKeys($arrVal['sral_p_4']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("year"))->sendKeys($arrVal['year']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("month"))->sendKeys($arrVal['month']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("day"))->sendKeys($arrVal['day']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("hour"))->sendKeys($arrVal['hour']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("min"))->sendKeys($arrVal['min']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("second"))->sendKeys($arrVal['second']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("entrant_admin_comment"))->sendKeys($arrVal['entrant_admin_comment']);
        
    $btnSubmitForm = "//button[contains(.,登録情報を確認する)]";
    $GLOBALS['driver']->findElement(WebDriverBy::xpath($btnSubmitForm))->click();

    //take creenshot
    $target_dir = __DIR__ . '/../data/screenshots/' . $GLOBALS['moduleName'] . date('Ymd');
    if (!file_exists($target_dir) && !is_dir($target_dir)) {
        mkdir($target_dir, 0777);
    }    
    $target_file = $target_dir . "/" . $arrVal['case_name'] . ".PNG";

    $tmp = $GLOBALS['driver']->takeScreenshot($target_file);

    $is_insert = EventEntrants::getInfo($arrVal);

    return $is_insert;
}

function checkValidatedFormJS() {
    
    $testInputArr = [];
    
    $testInputArr[] = 
    [
        'case_name'=> 'check empty input',
        'sral_p_1'=>'',
        'sral_p_2'=>'',
        'sral_p_3'=>'',
        'sral_p_4'=>'',
        'event_no'=>'',
        'year'    =>'',
        'month'   =>'',
        'day'     =>'',
        'hour'    =>'',
        'min'     =>'',
        'second'  =>'',
        'entrant_admin_comment'=>''
    ];
    $testInputArr[] = 
    [
        'case_name'=> 'check empty  シリアルNo',
        'sral_p_1'=>'1234',
        'sral_p_2'=>'1234',
        'sral_p_3'=>'1234',
        'sral_p_4'=>'',
        'event_no'=>'1',
        'year'    =>'2022',
        'month'   =>'03',
        'day'     =>'15',
        'hour'    =>'23',
        'min'     =>'59',
        'second'  =>'59',
        'entrant_admin_comment'=>''
    ];
    $testInputArr[] = 
    [
        'case_name'=> 'check validate number シリアルNo',
        'sral_p_1'=>'abcd',
        'sral_p_2'=>'1234',
        'sral_p_3'=>'12345',
        'sral_p_4'=>'123',
        'event_no'=>'1',
        'year'    =>'2022',
        'month'   =>'03',
        'day'     =>'15',
        'hour'    =>'23',
        'min'     =>'59',
        'second'  =>'59',
        'entrant_admin_comment'=>''
    ];
    $testInputArr[] = 
    [
        'case_name'=> 'check empty date 年',
        'sral_p_1'=>'1234',
        'sral_p_2'=>'1234',
        'sral_p_3'=>'12345',
        'sral_p_4'=>'123',
        'event_no'=>'1',
        'year'    =>'',
        'month'   =>'03',
        'day'     =>'15',
        'hour'    =>'23',
        'min'     =>'59',
        'second'  =>'59',
        'entrant_admin_comment'=>''
    ];
    $testInputArr[] = 
    [
        'case_name'=> 'check empty date 月',
        'sral_p_1'=>'1234',
        'sral_p_2'=>'1234',
        'sral_p_3'=>'12345',
        'sral_p_4'=>'123',
        'event_no'=>'1',
        'year'    =>'2022',
        'month'   =>'',
        'day'     =>'15',
        'hour'    =>'23',
        'min'     =>'59',
        'second'  =>'59',
        'entrant_admin_comment'=>''
    ];
    $testInputArr[] = 
    [
        'case_name'=> 'check empty date 日',
        'sral_p_1'=>'1234',
        'sral_p_2'=>'1234',
        'sral_p_3'=>'12345',
        'sral_p_4'=>'123',
        'event_no'=>'1',
        'year'    =>'2022',
        'month'   =>'03',
        'day'     =>'',
        'hour'    =>'23',
        'min'     =>'59',
        'second'  =>'59',
        'entrant_admin_comment'=>''
    ];
    $testInputArr[] = 
    [
        'case_name'=> 'check empty date 時',
        'sral_p_1'=>'1234',
        'sral_p_2'=>'1234',
        'sral_p_3'=>'12345',
        'sral_p_4'=>'123',
        'event_no'=>'1',
        'year'    =>'2022',
        'month'   =>'03',
        'day'     =>'15',
        'hour'    =>'',
        'min'     =>'59',
        'second'  =>'59',
        'entrant_admin_comment'=>''
    ];
    $testInputArr[] = 
    [
        'case_name'=> 'check empty date 分',
        'sral_p_1'=>'1234',
        'sral_p_2'=>'1234',
        'sral_p_3'=>'12345',
        'sral_p_4'=>'123',
        'event_no'=>'1',
        'year'    =>'2022',
        'month'   =>'03',
        'day'     =>'15',
        'hour'    =>'23',
        'min'     =>'',
        'second'  =>'59',
        'entrant_admin_comment'=>'',
    ];
    $testInputArr[] = 
    [
        'case_name'=> 'check empty date 秒',
        'sral_p_1'=>'1234',
        'sral_p_2'=>'1234',
        'sral_p_3'=>'12345',
        'sral_p_4'=>'123',
        'event_no'=>'1',
        'year'    =>'2022',
        'month'   =>'03',
        'day'     =>'15',
        'hour'    =>'23',
        'min'     =>'59',
        'second'  =>'',
        'entrant_admin_comment'=>''
    ];
    $testInputArr[] = 
    [
        'case_name'=> 'check enter string in 申込日',
        'sral_p_1'=>'1234',
        'sral_p_2'=>'1234',
        'sral_p_3'=>'12345',
        'sral_p_4'=>'123',
        'event_no'=>'1',
        'year'    =>'aaaa',
        'month'   =>'bb',
        'day'     =>'cc',
        'hour'    =>'2a',
        'min'     =>'2b',
        'second'  =>'2c',
        'entrant_admin_comment'=>''
    ];
    $testInputArr[] = 
    [
        'case_name'=> 'check date larger than permission 開催回',
        'sral_p_1'=>'1234',
        'sral_p_2'=>'1234',
        'sral_p_3'=>'12345',
        'sral_p_4'=>'123',
        'event_no'=>'1',
        'year'    =>'2022',
        'month'   =>'03',
        'day'     =>'16',
        'hour'    =>'23',
        'min'     =>'59',
        'second'  =>'',
        'entrant_admin_comment'=>''
    ];
    //write log
    $fHandler = fopen(__DIR__. '/../data/logs/'. $GLOBALS['moduleName'] . date('Y-m-d') . '.txt', 'a+');
    //check all case
    $length = count($testInputArr);
    fwrite($fHandler, "Result " . date('Y-m-d H:i:s') . "\n");
    for ($i = 0; $i < $length; $i++) {
        $is_insert = (autoInputForm($testInputArr[$i]))? "inserted" : "not inserted";
        $line = "insert case_name ". $testInputArr[$i]['case_name'] . " is: " . $is_insert . "\n";
        fwrite($fHandler, $line);
    }
    fclose($fHandler);
}

$driver->quit();
