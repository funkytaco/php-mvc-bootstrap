<?php

namespace Main\Traits\DB;

    trait PostgreSQL {

    public function __construct() {
        $this->dbname = 'clouddbpostgres';
        $this->dbhost = '127.0.0.1';
        $this->dsn = 'pgsql:dbname='. $this->dbname .';host='. $this->dbhost;
        $user = 'lgonzalez';
        $password = '';

        try {
            $this->dbh = new \PDO($this->dsn, $user, $password);
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);


        } catch (PDOException $e) {
            //echo 'Database connection failed: ' . $e->getMessage();
        }


    }

        function query($query, $params = null) {
            if (!is_null($params) && !is_array($params)) {
                $params = array($params);
            }
            $stmt = $this->dbh->prepare($query);
            $stmt->execute($params);
            return $stmt;
        }


    }