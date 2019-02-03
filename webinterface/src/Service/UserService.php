<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 15:57
 */

namespace App\Service;

use App\Entity\User;

class UserService
{

    /**
     * @var Database
     * */
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function hasUser(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user'] instanceof User;
    }

    public function fetchUser(): User
    {
        if ($this->hasUser()) {
            return $_SESSION['user'];
        }

        return false;
    }

    public function storeUser(User $user)
    {
        $_SESSION['user'] = $user;
    }

    public function destroy()
    {
        session_destroy();
    }

    public function authorize(string $token)
    {
        $qb = new QueryBuilder();
        $userData = $this->database->execute(
            $qb->select('*', 'user', [
                'token' => $token,
            ])
        );

        if (! is_array($userData)) {
            return false;
        }

        return true;
    }

    public function auth(string $username, string $password)
    {
        $qb = new QueryBuilder();
        $userData = $this->database->execute(
            $qb->select('*', 'user', [
                'username' => $username,
                'password' => hash('ripemd160', $password)
            ])
        );

        if (! is_array($userData)) {
            return false;
        }

        $userData['token'] = hash('ripemd160', date('U') . rand(100,999));

        $this->database->execute(
            $qb->update('user', [
                'username' => $username,
                'password' => hash('ripemd160', $password)
            ], $userData)
        );

        $user = new User();

        foreach ($userData as $key => $value)
        {
            $user->$key = $value;
        }

        $this->storeUser($user);

        return $user;
    }

}