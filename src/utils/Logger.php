<?php

    /**
     * Created by PhpStorm.
     * User: prasad.p
     * Date: 25/09/16
     * Time: 1:03 AM
     */
    class Logger
    {

        public static function info($msg)
        {
            self::log('Info:: ' . json_encode($msg) . "\n\n");
        }

        private static function log($msg)
        {
            error_log(date('Y-m-d H:i:s') . ' ' . $msg, 3, 'log/' . date('Ymd') . '.log');
        }

        public static function error($msg)
        {
            self::log('Error:: ' . json_encode($msg) . "\n\n");
        }

        public static function warning($msg)
        {
            self::log('Warning:: ' . json_encode($msg) . "\n\n");
        }

        public static function debug($msg)
        {
            self::log('Debug:: ' . json_encode($msg) . "\n\n");
        }

    }