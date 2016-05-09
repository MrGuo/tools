<?php

class Config {

    
    private function __construct() {}

    static function instance() {
        static $singleton = null;
        if (!isset($singleton)) {
            $singleton = new self();
        }   
        return $singleton;
    }

    public function dbConfig($dbName) {
        $config = array();
        if ($dbName == 'yanchupiao') {
            $config = array(
                'host' => 'xxx',
                'port' => 6306,
                'password' => '',
                'user' => '', 
            );
        }
        return $config;
    }
}

class DB {

    private $host;
    private $port;
    private $user;
    private $password;
    private $dbName;

    private $link;

    private function __construct($dbName) {
        $this->config = Config::instance()->dbConfig($dbName);    
        $this->dbName = $dbName;
    }

    static function instance($db_name) {
        static $singleton = array();
        if (!isset($singleton[$db_name])) {
            $singleton[$db_name] = new self($db_name);
        }
        return $singleton[$db_name];
    }

    private function initLink() {
        $host = $this->config['host'];
        $port = $this->config['port'];
        $user = $this->config['user'];
        $password = $this->config['password'];
        $this->link = mysql_connect($host. ':' . $port, $user, $password);
        if (!$this->link) {
            die("connect failure:" . mysql_error());
        }
        if (!mysql_select_db($this->dbName)) {
            die("unable to select database: " . mysql_error());
        }
    }

    public function read($query) {
        if (!$this->link) {
            $this->initLink();
        }
        $result = mysql_query($query, $this->link);
        if (!$result) {
            $message = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;
            die($message);
        }
        $r = array();
        while ($row = mysql_fetch_assoc($result)) {
            $r[] = $row;
        }
        return $r;
    }
}
