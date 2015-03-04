<?php

namespace Main\Mock\Traits;

    trait QueryData {

        private function mustachify($user) { return array("name" => "$user"); }

        public function appName() {
            return 'PHP Template Seed Project';
        }

        public function getLintHtmlFromTrait() {
            return '<script type="text/javascript">
                javascript:(function(){var s=document.createElement("script");s.onload=function(){bootlint.showLintReportForCurrentDocument([]);};s.src="https://maxcdn.bootstrapcdn.com/bootlint/latest/bootlint.min.js";document.body.appendChild(s)})();
                </script>';
        }

        public function getUsers() {

            $users = [self::mustachify('@funkytaco'),self::mustachify('@PatrickLouys'),self::mustachify('@Rican7')];
            return $users;
        }
        

    }