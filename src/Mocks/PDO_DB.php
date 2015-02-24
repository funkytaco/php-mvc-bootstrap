<?php
namespace Mock;

class Database {

    private $dbname;
    private $dbhost;
    private $user;
    private $password;

    public function __construct() {

    }

    public function query() {

        
    }

    public function getUsers() {

        function mustachify($user) { return array('name' => $user); }
        $users = [mustachify('@funkytaco'),mustachify('@PatrickLouys'),mustachify('@Rican7')];
        return $users;
    }



}