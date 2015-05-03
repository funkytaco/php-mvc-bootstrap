<?php

namespace Tasks;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class DevTasks {

    private $composer;
    private $event;

    private static $foreground = array(
      'black' => '0;30',
      'dark_gray' => '1;30',
      'red' => '0;31',
      'bold_red' => '1;31',
      'green' => '0;32',
      'bold_green' => '1;32',
      'brown' => '0;33',
      'yellow' => '1;33',
      'blue' => '0;34',
      'bold_blue' => '1;34',
      'purple' => '0;35',
      'bold_purple' => '1;35',
      'cyan' => '0;36',
      'bold_cyan' => '1;36',
      'white' => '1;37',
      'bold_gray' => '0;37',
     );
     
     private static $background = array(
          'black' => '40',
          'red' => '41',
          'magenta' => '45',
          'yellow' => '43',
          'green' => '42',
          'blue' => '44',
          'cyan' => '46',
          'light_gray' => '47',
     );

    private static function ansiFormat($type = 'INFO', $str) {
        $types = array(

            'INFO' => self::$foreground['white'],
            'NOTICE' => self::$foreground['yellow'],
            'CONFIRM: Y/N' => self::$background['magenta'],
            'WARNING' => self::$background['red'],
            'ERROR' => self::$background['red'],
            'EXITING' => self::$background['yellow'],
            'DANGER' => self::$foreground['bold_red'],
            'SUCCESS' => self::$foreground['green'],
            'INSTALL' => self::$background['green'],
            'RUNNING>' => self::$foreground['white'],
            'COPYING>' => self::$foreground['white'],
            'MKDIR>' => self::$foreground['white']

        );

        $ansi_start = "\033[". $types[$type] ."m";
        $ansi_end = "\033[0m";
        $ansi_type_start = "\033[". $types['INFO'] ."m";


        return $ansi_type_start . "[$type] " . $ansi_end . $ansi_start .  $str . $ansi_end . PHP_EOL;
    }

    public static function startDevelopmentWebServer($event) {
      
        //$timeout = $event->getComposer()->getConfig()->get('process-timeout');
        $port = 8000;
        echo self::ansiFormat('INFO','Starting webserver on port '. $port);
        echo exec('php -S localhost:'. $port .' public/index.php');

    }

    private static function delete_assets_recursive($dir) { 

        $files = array_diff(scandir($dir), array('.','..')); 

        foreach ($files as $file) { 
            (is_dir("$dir/$file")) ? self::delete_assets_recursive("$dir/$file") : unlink("$dir/$file"); 
        } 

        return rmdir($dir); 
    }


    private static function copy_assets_recursive($source, $destination, $event) {
        echo self::ansiFormat('INFO', 'SOURCE DIR: '. $source);
        echo self::ansiFormat('INFO', 'DESTINATION DIR: '. $destination);


        if (!file_exists($source) || $destination ==  __DIR__ . '/public/assets/') {
            return false;
        }

        if (file_exists($destination)) {
            $io = $event->getIO();

            echo self::ansiFormat('WARNING', 'DESTINATION EXISTS! BACKUP IF NECESSARY!: '. $destination);

            if ($io->askConfirmation(self::ansiFormat('CONFIRM: Y/N', 'Delete target directory?'), false)) {
                
                self::delete_assets_recursive($destination); //Destructive! Your destination must be correct!

            } else {
                    echo self::ansiFormat('EXITING', 'Cancelled Bootstrap Post-Install Tasks');
                    exit;
            }

        }

        mkdir($destination, 0755, true);

        foreach (
        $directoryPath = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
        \RecursiveIteratorIterator::SELF_FIRST) as $file
        ) {
            if ($file->isDir()) {
                echo self::ansiFormat('MKDIR>', $file);
                mkdir($destination . DIRECTORY_SEPARATOR . $directoryPath->getSubPathName(), 0755);
            } else {
                echo self::ansiFormat('COPYING>', $file);
                copy($file, $destination . DIRECTORY_SEPARATOR . $directoryPath->getSubPathName());
            }
        }

        return true;
    }

    public static function postUpdate(Event $event) {
        echo self::ansiFormat('RUNNING>', 'Post-Update Tasks');
    }

    public static function copyAssets($source, $destination, $event) {


        try {

            if (self::copy_assets_recursive($source, $destination, $event) == true) {
                echo self::ansiFormat('SUCCESS', 'Copied assets from "'.realpath($source).'" to "'.realpath($destination).'".'.PHP_EOL);
            } else {
                echo self::ansiFormat('ERROR', 'Copy failed! Unable to copy assets from "'.realpath($source)
                    .'" to "'.realpath($destination).'"');
            }
            } catch(Exception $e) {
                echo self::ansiFormat('ERROR', 'Copy failed! Unable to copy assets from "'.realpath($source)
                    .'" to "'.realpath($destination).'"');
        }                   

    }

    public static function postPackageReinstall(Event $event) {
        self::copy_assets_for_event($event);
    }


private static function copy_assets_for_event(Event $event) {

        echo self::ansiFormat('RUNNING>', 'Bootstrap Post-Install Tasks');
        $extra = $event->getComposer()->getPackage()->getExtra();

        if(is_array($extra)) {
            if(array_key_exists('bootstrap-assets', $extra)) {

                foreach($extra['bootstrap-assets'] as $key => $value) {

                    if ($key == 'copy-assets') {
                        $copy_assets = $value;
                        continue;
                    } else {
                        $arrAsset = $value;
                    }

                    if ($copy_assets == true && is_array($arrAsset)) {
                        self::copyAssets($arrAsset['source'] , $arrAsset['target'], $event);
                    } 
                }
              
            }
        }

        $css_dir = 'public/assets/css/themes/';
        if (is_dir($css_dir)) {
            self::delete_assets_recursive($css_dir);
        }

        mkdir($css_dir, 0755, true);

        copy('vendor/twbs/bootstrap/docs/examples/dashboard/dashboard.css', $css_dir . 'dashboard.css');
        copy('vendor/twbs/bootstrap/docs/examples/cover/cover.css', $css_dir .'cover.css');


    }



    private static function copy_assets_for_package(PackageEvent $event) {

        echo self::ansiFormat('RUNNING>', 'Bootstrap Post-Install Tasks');
        $extra = $event->getComposer()->getPackage()->getExtra();

        if(is_array($extra)) {
            if(array_key_exists('bootstrap-assets', $extra)) {

                foreach($extra['bootstrap-assets'] as $key => $value) {

                    if ($key == 'copy-assets') {
                        $copy_assets = $value;
                        continue;
                    } else {
                        $arrAsset = $value;
                    }

                    if ($copy_assets == true && is_array($arrAsset)) {
                        self::copyAssets($arrAsset['source'] , $arrAsset['target'], $event);
                    } 
                }
              
            }
        }

        $css_dir = 'public/assets/css/themes/';
        if (is_dir($css_dir)) {
            self::delete_assets_recursive($css_dir);
        }

        mkdir($css_dir, 0755, true);

        copy('vendor/twbs/bootstrap/docs/examples/dashboard/dashboard.css', $css_dir . 'dashboard.css');
        copy('vendor/twbs/bootstrap/docs/examples/cover/cover.css', $css_dir .'cover.css');


    }

    public static function postPackageInstall(PackageEvent $event) {

        echo self::ansiFormat('RUNNING>', 'Post-Install Tasks');

        $installedPackage = $event->getOperation()->getPackage();

        echo self::ansiFormat('INSTALL', $installedPackage);

        if (strstr($installedPackage,'twbs/bootstrap') == true) {

          self::copy_assets_for_package($event);

        } else {

            echo self::ansiFormat('INFO', $installedPackage);

        }
    }


}