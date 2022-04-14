<?php 

use Database\Database;
use Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
require_once(__DIR__ .'/../vendor/autoload.php');

require_once(__DIR__ .'/EventMaster.php');
require_once(__DIR__ . '/../lib/common.php');

date_default_timezone_set( "Asia/Tokyo" );

$host = "http://localhost:4444/wd/hub";

global $driver, $moduleName;

$driver = Facebook\WebDriver\Remote\RemoteWebDriver::create($host, Facebook\WebDriver\Remote\DesiredCapabilities::chrome());

$moduleName = "event-holding-list";

$url = "http://192.168.3.211:8006/event/nkb/App/Views/EventMaster/index.php";

$driver->get($url);

$driver->manage()->window()->maximize();

checkValidatedFormJS();

function resetInputForm() {
    $GLOBALS['driver']->findElement(WebDriverBy::id("event_no"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("event_name1"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("event_name2"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("event_comment"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("year_start"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("month_start"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("day_start"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("hour_start"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("min_start"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("second_start"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("year_end"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("month_end"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("day_end"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("hour_end"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("min_end"))->clear();
    $GLOBALS['driver']->findElement(WebDriverBy::id("second_end"))->clear();
}
function autoInputForm($arrVal) {
    resetInputForm();
    $GLOBALS['driver']->findElement(WebDriverBy::id("event_no"))->sendKeys($arrVal['event_no']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("event_name1"))->sendKeys($arrVal['event_name1']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("event_name2"))->sendKeys($arrVal['event_name2']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("event_comment"))->sendKeys($arrVal['event_comment']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("year_start"))->sendKeys($arrVal['year_start']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("month_start"))->sendKeys($arrVal['month_start']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("day_start"))->sendKeys($arrVal['day_start']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("hour_start"))->sendKeys($arrVal['hour_start']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("min_start"))->sendKeys($arrVal['min_start']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("second_start"))->sendKeys($arrVal['second_start']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("year_end"))->sendKeys($arrVal['year_end']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("month_end"))->sendKeys($arrVal['month_end']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("day_end"))->sendKeys($arrVal['day_end']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("hour_end"))->sendKeys($arrVal['hour_end']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("min_end"))->sendKeys($arrVal['min_end']);
    $GLOBALS['driver']->findElement(WebDriverBy::id("second_end"))->sendKeys($arrVal['second_end']);
    
    $btnSubmitForm = "//button[contains(.,登録情報を確認する)]";
    $GLOBALS['driver']->findElement(WebDriverBy::xpath($btnSubmitForm))->click();

    $arrVal['up_ymdhms'] = date('Y-m-d H:i:s');

    $is_insert = EventMaster::getInfo($arrVal);
    return $is_insert;
}

function checkValidatedFormJS() {
    $testInputArr = [];
    
    $testInputArr[] =
    [
        'case_name' => 'normal case',
        'event_no' => '1710',
        'event_name1' => 'case 1710',
        'event_name2' => 'case 1710_2',
        'event_comment' => 'check normal case',
        'year_start' => '2025',
        'month_start' => '01',
        'day_start' => '02',
        'hour_start' => '03',
        'min_start' => '0',
        'second_start' => '0',
        'year_end' => '2025',
        'month_end' => '01',
        'day_end' => '05',
        'hour_end' => '05',
        'min_end' => '59',
        'second_end' => '59',
        'date_start' => '2025-01-02 03:00:00',
        'date_end'  => '2025-01-05 05:59:59'
    ];
    //case 2: check empty input
    $testInputArr[] =
        [
            'case_name' => 'check empty input',
            'event_no' => ' ',
            'event_name1' => ' ',
            'event_name2' => ' ',
            'event_comment' => ' ',
            'year_start' => ' ',
            'month_start' => ' ',
            'day_start' => ' ',
            'hour_start' => ' ',
            'min_start' => ' ',
            'second_start' => ' ',
            'year_end' => ' ',
            'month_end' => ' ',
            'day_end' => ' ',
            'hour_end' => ' ',
            'min_end' => ' ',
            'second_end' => ' ',
            'date_start' => ' ',
            'date_end'  => ' '
        ];
    //case 3: check event_no exist
    $testInputArr[] =
        [
            'case_name' => 'check event_no exist',
            'event_no' => '2',
            'event_name1' => 'case 2',
            'event_name2' => 'case 2_2',
            'event_comment' => 'check event_no exist',
            'year_start' => ' ',
            'month_start' => ' ',
            'day_start' => ' ',
            'hour_start' => ' ',
            'min_start' => ' ',
            'second_start' => ' ',
            'year_end' => ' ',
            'month_end' => ' ',
            'day_end' => ' ',
            'hour_end' => ' ',
            'min_end' => ' ',
            'second_end' => ' ',
            'date_start' => ' ',
            'date_end'  => ' '
        ];
    //case 4: check overlapped date start
    $testInputArr[] =
        [
            'case_name' => 'check overlapped date start',
            'event_no' => '1711',
            'event_name1' => 'case 1711',
            'event_name2' => 'case 1711_2',
            'event_comment' => 'check overlapped date start',
            'year_start' => '2025',
            'month_start' => '01',
            'day_start' => '02',
            'hour_start' => '03',
            'min_start' => '0',
            'second_start' => '0',
            'year_end' => '2025',
            'month_end' => '02',
            'day_end' => '04',
            'hour_end' => '05',
            'min_end' => '59',
            'second_end' => '59',
            'date_start' => '2025-01-02 03:00:00',
            'date_end'  => '2025-02-04 05:59:59'
        ];
    //case 5: check overlapped date end
    $testInputArr[] =
        [
            'case_name' => 'check overlapped date end',
            'event_no' => '1711',
            'event_name1' => 'case 1711',
            'event_name2' => 'case 1711_2',
            'event_comment' => 'check overlapped date end',
            'year_start' => '2025',
            'month_start' => '01',
            'day_start' => '03',
            'hour_start' => '03',
            'min_start' => '0',
            'second_start' => '0',
            'year_end' => '2025',
            'month_end' => '02',
            'day_end' => '04',
            'hour_end' => '05',
            'min_end' => '59',
            'second_end' => '59',
            'date_start' => '2025-01-03 03:00:00',
            'date_end'  => '2025-02-04 05:59:59'
        ];
    //case 6: check overlapped event with date_start(new event) < date_start(old event) and date_end(new event) > date_end(old event) 2025-01-02->2025-01-05
    $testInputArr[] =
        [
            'case_name' => 'check overlapped event with date_start(new event) < date_start(old event) and date_end(new event) > date_end(old event)',
            'event_no' => '1711',
            'event_name1' => 'case 1711',
            'event_name2' => 'case 1711_2',
            'event_comment' => 'check overlapped event',
            'year_start' => '2025',
            'month_start' => '01',
            'day_start' => '01',
            'hour_start' => '03',
            'min_start' => '0',
            'second_start' => '0',
            'year_end' => '2025',
            'month_end' => '01',
            'day_end' => '06',
            'hour_end' => '05',
            'min_end' => '59',
            'second_end' => '59',
            'date_start' => '2025-01-01 03:00:00',
            'date_end'  => '2025-01-06 05:59:59'
        ];
    //case 7: check overlapped event with date_start(new_event) > date_start(old event) and date_end(new event) > date_end(old event) 2025-01-02->2025-01-05
    $testInputArr[] =
        [
            'case_name' => 'check overlapped event with date_start(new_event) > date_start(old event) and date_end(new event) > date_end(old event)',
            'event_no' => '1711',
            'event_name1' => 'case 1711',
            'event_name2' => 'case 1711_2',
            'event_comment' => 'check overlapped event',
            'year_start' => '2025',
            'month_start' => '01',
            'day_start' => '03',
            'hour_start' => '03',
            'min_start' => '0',
            'second_start' => '0',
            'year_end' => '2025',
            'month_end' => '01',
            'day_end' => '04',
            'hour_end' => '05',
            'min_end' => '59',
            'second_end' => '59',
            'date_start' => '2025-01-03 03:00:00',
            'date_end'  => '2025-01-04 05:59:59'
        ];
    //case 8: check unvalid input
    $testInputArr[] =
        [
            'case_name' => 'check unvalid input',
            'event_no' => 'aaaa',
            'event_name1' => 'case 1711',
            'event_name2' => 'case 1711_2',
            'event_comment' => 'check overlapped event',
            'year_start' => 'aaaa',
            'month_start' => 'aa',
            'day_start' => 'aa',
            'hour_start' => 'aa',
            'min_start' => 'aa',
            'second_start' => 'aa',
            'year_end' => 'aaaa',
            'month_end' => 'aa',
            'day_end' => 'aa',
            'hour_end' => 'aa',
            'min_end' => 'aa',
            'second_end' => 'aa',
            'date_start' => 'aaaa-aa-aa aa:aa:aa',
            'date_end'  => 'aaaa-aa-aa aa:aa:aa'
        ];

    //open modal
    $btnOpenModal = "//button[contains(.,新規イベント情報追加)]";
    $GLOBALS['driver']->findElement(WebDriverBy::xpath($btnOpenModal))->click();

    //write log
    $fHandler = fopen(__DIR__. '/../data/logs/'. $GLOBALS['moduleName'] . date('Y-m-d') . '.txt', 'w+');
    //check all case
    $length = count($testInputArr);
    for ($i = 0; $i < $length; $i++) {
        $GLOBALS['driver']->executeScript("if($('#informationModal').data('bs.modal')===undefined || !$('#informationModal').data('bs.modal')._isShown) { $('#informationModal').modal('show') }");
        $line = "$testInputArr[$i]['case_name']: " . autoInputForm($testInputArr[$i]) . "\n";
        fwrite($fHandler, $line);
    }
    fclose($fHandler);
}

$driver->quit();
