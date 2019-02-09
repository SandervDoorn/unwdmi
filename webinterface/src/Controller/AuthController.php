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
use App\Service\Database;
use App\Service\UserService;

class AuthController
{

    private $database;

    private $userService;

    public function __construct()
    {
        /*$this->database = new Database(
            'localhost',
            'weather-project',
            'weather',
            'weather-project'
        );
        $this->database->connect();*/

        $this->userService = new UserService();
    }

    public function login(Request $request, Response $response)
    {
        if ($this->userService->hasUser()) {
            $response->addHeader('Location', '/dashboard');
            return $response;
        }

        if ($request->isPost()) {
            $postData = $request->getPost();

            $username = $postData['username'];
            $password = $postData['password'];

            $user = $this->userService->auth($username, $password);

            if (! $user instanceof User) {
                return [
                    'valid' => false
                ];
            }

            $response->addHeader('Location', '/dashboard');
            return $response;
        }

        return [];
    }

    public function lockScreen(Request $request, Response $response)
    {
        if (!$this->userService->hasUser()) {
            $response->addHeader('Location', '/auth/login');

            return $response;
        }

        $user = $this->userService->fetchUser();
        $this->userService->destroy();

        return [
            'username' => $user->getUsername()
        ];
    }

    public function logout(Request $request, Response $response)
    {
        $this->userService->destroy();
        $response->addHeader('Location', '/');
        return $response;
    }

}