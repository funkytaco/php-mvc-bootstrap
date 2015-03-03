<?php
namespace Main\Mock;
class PDO extends \PDO
{
    use \Main\Mock\Traits\QueryData;

    public function __construct ()
    {}
}