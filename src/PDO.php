<?php
namespace Main;

class PDO extends \PDO {
    /** Put all of your queries into a traits file **/
    use \Main\Traits\QueryData;
}