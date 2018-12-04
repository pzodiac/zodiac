<?php
/**
 * Created by PhpStorm.
 * User: nhanphong
 * Date: 12/3/18
 * Time: 4:12 PM
 */

chdir(dirname(__DIR__));

define('ROOT', __DIR__);
define('HOST_HASH', substr(md5($_SERVER['HTTP_HOST']), 0, 12));

if (isset($_SERVER['APPLICATION_ENV'])) {
    $applicationEnv = ($_SERVER['APPLICATION_ENV'] ? $_SERVER['APPLICATION_ENV'] : 'production');
} else {
    $applicationEnv = (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production');
}

define('APPLICATION_ENV', $applicationEnv);
define('BASE_URL', (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]");

$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

define('APPLICATION_PATH', __DIR__ . '/frontend');
require_once APPLICATION_PATH . '/Bootstrap.php';

define('CORE_PATH', __DIR__ . '/core');
$bootstrap = new Zodiac\Bootstrap();
$bootstrap->run();