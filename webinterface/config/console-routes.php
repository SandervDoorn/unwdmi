<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 14:28
 */

namespace App;

use App\Controller\AuthController;
use App\Controller\ConsoleController;
use App\Controller\ErrorController;
use App\Controller\IndexController;
use App\Controller\MainController;

return [
    'home' => [
        'route' => 'server-start [0-9]+',
        'controller' => ConsoleController::class,
        'action' => 'index',
        'template' => 'index/index',
        'layout' => 'layout/default'
    ],
];