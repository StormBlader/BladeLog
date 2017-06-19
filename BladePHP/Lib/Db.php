<?php
namespace Lib;
		
Class Db {

	//用于存储数据库连接    
	protected $conn; 
	
	//连接数据库
	public function __construct() {
		$this->connect();
	}

    /**
     * 连接数据库
     */
    public function connect() 
    {
		$db = $GLOBALS['db'];
        $this->conn = new \PDO("mysql:host={$db['host']};port={$db['port']};dbname={$db['database']}", $db['username'], $db['password'], [
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;",
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
	}

    public function getConnect()
    {
        return $this->conn;
    }

    public function __destruct() {
		$this->conn = null;
	}
		
}
