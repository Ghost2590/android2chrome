<?php
namespace Android2Chrome;

// defined('WS_EXEC') or die('Restricted access');

class BaseModel{

    protected function getDBO ($type = 'read') {
//        $db = MySqlDatabase::getInstance();
//        try {
//            $db->connect('localhost', 'root', '', 'android2chrome');
//            return $db;
//        }
//        catch (Exception $e) {
//            die($e->getMessage());
//        }
        try {
            $db = new WSDBMysql($type);
//            $db->connect('localhost', 'root', '', 'android2chrome');
            return $db;
        }
        catch (Exception $e) {
            die($e->getMessage());
        }

    }
}