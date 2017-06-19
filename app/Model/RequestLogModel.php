<?php
namespace App\Model;
use Lib\Db as LibDB;

class RequestLogModel
{
    public static function createLog($data)
    {
        if(empty($data)) {
            return false;
        }

        $table = self::getTable($data['request_time']);
        $insert_attrs = [];
        $insert_values = [];
        $bind_values = [];
        foreach($data as $key => $value) {
            if(empty($key) || empty($value)) {
                return false;
            }
            $insert_attrs[] = "`$key`";
            $insert_values[] = ":$key"; 
            $bind_values[":$key"] = $value;
        }

        $insert_attrs = implode(',', $insert_attrs);
        $insert_values = implode(',', $insert_values);
        $sql = "insert into `{$table}`({$insert_attrs}) values ({$insert_values})";
        $pdo = getInstance('Lib\Db')->getConnect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($bind_values);

        return $pdo->lastInsertId();
    }

    public static function getTable($date)
    {
        $db = getInstance('Lib\Db');
        $format_date = date('Ymd', strtotime($date));
        $table = $GLOBALS['db']['prefix'] . "request_log_$format_date";

        $ret = self::isTableExists($date);
        if($ret) {
            return $table;
        }

        //没有则新建
        $sql = "create table $table like `" . $GLOBALS['db']['prefix'] . "request_log`";
        try{
            $ret = $db->getConnect()->exec($sql);
            return $table;
        }catch(Exception $e) {
            return false;
        }

        return false;
    }

    public static function isTableExists($date)
    {
        $db = getInstance('Lib\Db');
        $format_date = date('Ymd', strtotime($date));
        if(empty($date) || ($format_date == false)) {
            return $GLOBALS['db']['prefix'] . "request_log";
        }

        $table = $GLOBALS['db']['prefix'] . "request_log_$format_date";
        $sql = "show create table `$table`";
        try{
            $ret = $db->getConnect()->query($sql);
        }catch(\Exception $e) {
            if($e->getCode() == '42S02') {
                $ret = false;
            }else {
                print $e->getMessage();
                exit;
            }
        }
        
        return $ret ? true : false;
    }

    public static function findByPage($date, $begin_date, $end_date, $min_consume, $other_where = null, $page = 1, $page_size = 15)
    {
        $table_flag = self::isTableExists($date);
        if($table_flag == false) {
            return [];
        }

        $table = self::getTable($date);
        $sql_where = "1 = 1";
        $sql_bind = [];
        if(!empty($begin_date)) {
            $sql_where .= " and request_time >= :begin_date";
            $sql_bind[':begin_date'] = $begin_date;
        }

        if(!empty($end_date)) {
            $sql_where .= " and request_time <= :end_date";
            $sql_bind[':end_date'] = $end_date;
        }

        if(!empty($min_consume)) {
            $sql_where .= " and request_consume >= :request_consume";
            $sql_bind[':request_consume'] = $min_consume;
        }

        $other_where = is_null($other_where) ? [] : $other_where;
        foreach($other_where as $key => $value) {
            $sql_where .= " and {$key} = :{$key}";
            $sql_bind[":$key"] = $value;
        }

        $offset = intval($page - 1) * $page_size;
        $sql = "select * from {$table} where $sql_where order by request_time desc limit $offset,$page_size";
        $pdo = getInstance('Lib\Db')->getConnect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($sql_bind);

        return $stmt->fetchAll();
    }

}
