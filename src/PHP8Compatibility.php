<?php
namespace Main;

/**
 * PHP 8.2 Compatibility Layer
 * This file provides compatibility fixes for running the application on PHP 8.2
 */

if (!class_exists('ReturnTypeWillChange')) {
    #[\Attribute]
    class ReturnTypeWillChange {
        public function __construct() {}
    }
}

// Add compatibility attribute to Klein's DataCollection getIterator method
if (!function_exists('add_klein_compatibility')) {
    function add_klein_compatibility() {
        if (class_exists('\Klein\DataCollection\DataCollection')) {
            $reflection = new \ReflectionClass('\Klein\DataCollection\DataCollection');
            if ($reflection->hasMethod('getIterator')) {
                $method = $reflection->getMethod('getIterator');
                if (!$method->getAttributes(\ReturnTypeWillChange::class)) {
                    eval('
                        namespace Klein\DataCollection {
                            class DataCollection extends \Klein\DataCollection\AbstractDataCollection {
                                #[\ReturnTypeWillChange]
                                public function getIterator() {
                                    return parent::getIterator();
                                }
                            }
                        }
                    ');
                }
            }
        }
    }
}

// Initialize compatibility layer
add_klein_compatibility();
