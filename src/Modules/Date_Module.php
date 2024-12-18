<?php
namespace Main\Modules;

    Class Date_Module {

        public function getDate() {
            return @date('D. F jS, Y');
        }
    }
