<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 13:31
 */

namespace App\Controller;

use App\Entity\User;
use App\Request;
use App\Response;

class IndexController
{

    public function index(Request $request, Response $response)
    {
        if (!isset($_SESSION['user']) || !$_SESSION['user'] instanceof User) {
            $response->addHeader('Location', '/auth/login');

            return $response;
        }

        $response->addHeader('Location', '/dashboard');

        return $response;
    }

    public function api(Request $request, Response $response)
    {
        $response->addHeader('Content-Type', 'application/json');
        $response->setContent(json_encode([
            'version' => '0.1.1',
            'get_variables' => $request->getGet(),
            'post_variables' => $request->getPost(),
            'data' => [
                'id' => 1,
                'name' => 'Redmar',
                'password' => 'test123'
            ]
        ]));

        return $response;
    }

}