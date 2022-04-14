<?php

require_once(__DIR__ . '/../database/Database.php');

class EventEntrants extends \DatabaseConfig\Database
{   
    private static $table = 'event_nkb_entrants'; 

    public static function getInfo(array $arr) {
        extract($arr);
        $db = static::getDB();
        $query = "SELECT COUNT(*) as count FROM " . self::$table . " WHERE entrant_sral_no = '$sral_no' AND event_no = '$event_no' AND entrant_ymdhms = '$entrant_ymdhms' AND entrant_admin_comment = '$entrant_admin_comment'";
        $stmt = $db->query($query);
        $rs = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($rs['count'] == 1) {
            return true;
        }
        return false;
    }
}