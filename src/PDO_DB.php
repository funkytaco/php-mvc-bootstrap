<?php
namespace Main;

//use \Auryn\Provider;

class Database {

    private $dbname;
    private $dbhost;
    private $user;
    private $password;

    public function __construct() {
        $this->dbname = 'clouddb';
        $this->dbhost = '127.0.0.1';
        $this->dsn = 'mysql:dbname='. $this->dbname .';host='. $this->dbhost;
        $user = 'root';
        $password = '';

        try {
            $this->dbh = new \PDO($this->dsn, $user, $password);
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);


        } catch (PDOException $e) {
            //echo 'Database connection failed: ' . $e->getMessage();
        }


    }


    public function query($query, $params = null) {

        if (!is_null($params) && !is_array($params)) {
            $params = array($params);
        }
        $stmt = $this->dbh->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function getUsers() {

        function prepUserForMustache($user) { return array('name' => $user); }

        $query = $this->query("SELECT * FROM users");
        $rows = $query->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $users[]  = prepUserForMustache($row['name']);
        }

        return $users;
    }



}