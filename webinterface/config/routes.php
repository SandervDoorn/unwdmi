<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 14:28
 */

namespace App;

use App\Controller\AuthController;
use App\Controller\ErrorController;
use App\Controller\IndexController;
use App\Controller\MainController;

return [
    'home' => [
        'url' => '/',
        'controller' => IndexController::class,
        'action' => 'index',
        'template' => 'index/index',
        'layout' => 'layout/default'
    ],
    'dashboard' => [
        'url' => '/dashboard',
        'controller' => MainController::class,
        'action' => 'dashboard',
        'template' => 'main/dashboard',
        'layout' => 'layout/default'
    ],

    // USER
    'login' => [
        'url' => '/auth/login',
        'controller' => AuthController::class,
        'action' => 'login',
        'template' => 'auth/login',
        'layout' => 'layout/auth'
    ],
    'lock-screen' => [
        'url' => '/auth/lock-screen',
        'controller' => AuthController::class,
        'action' => 'lockScreen',
        'template' => 'auth/lock-screen',
        'layout' => 'layout/auth'
    ],
    'logout' => [
        'url' => '/auth/logout',
        'controller' => AuthController::class,
        'action' => 'logout'
    ],

    // API
    'api' => [
        'url' => '/api',
        'controller' => IndexController::class,
        'action' => 'api'
    ],

    // Restricted pages
    'not_found' => [
        'url' => '/404',
        'controller' => ErrorController::class,
        'action' => 'notFound',
        'template' => 'error/404',
        'layout' => 'layout/error'
    ],
    'not_allowed' => [
        'url' => '/403',
        'controller' => ErrorController::class,
        'action' => 'notAllowed',
        'template' => 'error/403',
        'layout' => 'layout/error'
    ],
];