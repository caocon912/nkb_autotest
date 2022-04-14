<?php

use PDO;

require_once(__DIR__ . '/../database/Database.php');

class EventMaster extends \DatabaseConfig\Database
{   
    private static $table = 'event_nkb_master'; 

    public static function getInfo(array $arr) {
        extract($arr);
        $db = static::getDB();
        $query = "SELECT COUNT(*) as count FROM " . self::$table . " WHERE event_no = '$event_no'
        AND event_name1 = '$event_name1' 
        AND event_name2 = '$event_name2'
        AND event_comment = '$event_comment' 
        AND event_start_ymdhms = '$event_start_ymdhms' 
        AND event_end_ymdhms = '$event_end_ymdhms'"; 
        // AND up_ymdhms = '$this->up_ymdhms'";
        $stmt = $db->query($query);
        $rs = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($rs['count'] == 1) {
            return true;
        }
        return false;
    }
}