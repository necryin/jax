<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Jax;

/**
 * Class ErrorHandler
 * @package Necryin\Jax
 */
class ErrorHandler
{
    /**
     * @var string Reserved memory against out of memory
     */
    private static $reservedMemory;

    /**
     * @return void
     */
    public static function register()
    {
        if(self::$reservedMemory === null) {
            self::$reservedMemory = str_repeat('x', 10240);
        }

        set_exception_handler(__CLASS__ . '::exceptionHandler');
        set_error_handler(__CLASS__ . '::errorHandler');
        register_shutdown_function(__CLASS__ . '::errorHandler');
    }

    /**
     * Error handler
     */
    public static function errorHandler()
    {
        self::$reservedMemory = null;

        $last_error = error_get_last();
        if($last_error && $last_error['type'] === E_ERROR) {
            error_log(sprintf("ERROR - File: %s; Line: %s; Message: %s", $last_error['file'], $last_error['line'], $last_error['message']));
            header("HTTP/1.0 500 Internal Server Error");
            echo "<div style='text-align: center; font-size: 48px;'>500 Internal Server Error</div>";
        }
    }

    /**
     * @param $exception
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        if ($exception instanceof \Exception) {
            error_log(sprintf("EXCEPTION - File: %s; Line: %s; Message: %s", $exception->getFile(), $exception->getLine(), $exception->getMessage()));
            header("HTTP/1.0 500 Internal Server Error");
            echo "<div style='text-align: center; font-size: 48px;'>500 Internal Server Error</div>";
        }
    }
}
