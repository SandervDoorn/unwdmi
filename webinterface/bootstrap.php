<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 13:24
 */

define('__ROOT__', __DIR__);

$loader = false;

if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
}

if(!$loader instanceof \Composer\Autoload\ClassLoader) {
    throw new \Error('Could not load vendor packages!');
}

session_start();

$appCopfig  = include __DIR__ . '/config/app_config.php';
$app        = new \App\App($appCopfig);

echo $app->run();