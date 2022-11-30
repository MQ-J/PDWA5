<?php

namespace Dannsbass;

use Exception;

final class ReplitDB
{
    /**
     * @var string $REPLIT_DB_URL
     * variable to store $REPLIT_DB_URL
     */
    public static $REPLIT_DB_URL = 'https://kv.replit.com/v0/eyJhbGciOiJIUzUxMiIsImlzcyI6ImNvbm1hbiIsImtpZCI6InByb2Q6MSIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJjb25tYW4iLCJleHAiOjE2Njk5MzUxNjYsImlhdCI6MTY2OTgyMzU2NiwiZGF0YWJhc2VfaWQiOiI3NTFlMDVmMS0yZTZiLTRkNzEtYmY1YS0wZGEzYmI2NTdjOTMiLCJ1c2VyIjoiTVFKIiwic2x1ZyI6IlBEV0E1In0.oGmiO_mkVCdM8HRV4kI6x3yW-gKTb0yQvyhgCh9R0MXrkDbAL8Y3PbziQc4urHXkTqLOqsQl4jMRzo_c548slQ';

    /**
     * @var string $REPLIT_DB_URL
     */
    public function __construct(string $REPLIT_DB_URL = '')
    {
        if (empty($REPLIT_DB_URL)) {
            self::$REPLIT_DB_URL = getenv('REPLIT_DB_URL');
        } else {
            self::$REPLIT_DB_URL = $REPLIT_DB_URL;
        }
    }
    /**
     * 
     */
    public static function getURL()
    {
        return self::$REPLIT_DB_URL;
    }
    /**
     * 
     */
    public static function http_request(array $content = [], string $method = 'POST', $path = '')
    {
        if (count($content) > 1) {
            throw new Exception('Content array must contain ONLY one key and one value');
        }

        if (empty($path)) {
            $url = self::$REPLIT_DB_URL;
        } else {
            $url = self::$REPLIT_DB_URL . $path;
        }

        $stream = [
            'http' => [
                'method' => $method,
                'content' => http_build_query($content)
            ]
        ];
        return file_get_contents($url, false, stream_context_create($stream));
    }
    /**
     * 
     */
    public static function set_data(string $key, string $value)
    {
        return self::http_request([$key => $value], 'POST');
    }
    /**
     * @var string | array $key
     * key of data to be deleted
     */
    public static function delete_data($key)
    {
        if (is_array($key)) {
            foreach ($key as $value) {
                self::http_request([], 'DELETE', '/' . $value);
            }
            return;
        }
        return self::http_request([], 'DELETE', '/' . $key);
    }
    /**
     * @var string $prefix
     * prefix of data key
     */
    public static function get_keys(string $prefix = '')
    {
        return file_get_contents(self::$REPLIT_DB_URL . '?prefix=' . $prefix) . PHP_EOL;
    }
    /**
     * @var string $key
     * data key
     */
    public static function get_data(string $key)
    {
        return file_get_contents(self::$REPLIT_DB_URL . '/' . $key);
    }

    public static function get(string $key)
    {
        return self::get_data($key);
    }

    public static function put(string $key, string $value)
    {
        return self::set_data($key, $value);
    }

    public static function set(string $key, string $value)
    {
        return self::set_data($key, $value);
    }

    public static function del($key)
    {
        return self::delete_data($key);
    }

    public static function keys($prefix = '')
    {
        return self::get_keys($prefix);
    }
}