<?php

namespace Main\Traits;

    trait QueryData {


        private function prepUserForMustache($user) { return array('name' => $user); }

        public function getUsers() {
            $users = [];
            self::prepUserForMustache($user);

            $query = $this->query("SELECT * FROM users");
            $rows = $query->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $users[]  = prepUserForMustache($row['name']);
            }

            return $users;
        }
        

    }