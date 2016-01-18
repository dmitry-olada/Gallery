<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 10.12.15
 * Time: 0:06
 */

define('CORE', __DIR__.'/../Core/');
define('ROOT', __DIR__.'/../');
define('BR', '<Br/>');
//var_dump($_SERVER['REQUEST_METHOD']);DIE;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once(CORE . 'Loader.php');
require_once(ROOT . '/vendor/autoload.php');
require_once(CORE . 'Application.php');

use \Core\Loader;
use \Core\Application;

Loader::register();
Loader::addNamespacePath('Core\\', CORE);

$app = new Application(new Lebran\Container());
$app->handle();




