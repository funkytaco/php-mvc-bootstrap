<?php

namespace Main\Traits;

    trait QueryData {


        private function prepUserForMustache($user) { return array('name' => $user); }

        public function getUsers() {
            $users = [];
            self::prepUserForMustache($user) { return array('name' => $user); }

            $query = $this->query("SELECT * FROM users");
            $rows = $query->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $users[]  = prepUserForMustache($row['name']);
            }

            return $users;
        }


        public function getFirstUser() {
            $users = [];

            $query = $this->query("SELECT * FROM users LIMIT 1");
            $rows = $query->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $user[]  = self::prepUserForMustache($row['name']);
            }

            return $user;
        }

        

    }