<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 13:29
 */

namespace App\Config;

use App\Controller\IndexController;

return [
    'router' => [
        'routes' => include __DIR__ . '/routes.php',
    ]
];