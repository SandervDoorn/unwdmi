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

    const CSV_LOCATION = __DIR__ . '/../../data/users.csv';

    /**
     * @var Database
     * */
    private $database;

    public function __construct($database = null)
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
        /*$qb = new QueryBuilder();
        $userData = $this->database->execute(
            $qb->select('*', 'user', [
                'token' => $token,
            ])
        );*/

        $userData = false;

        $database = $this->getCSV();
        foreach ($database as $record) {
            if ($record['token'] == $token) {
                $userData = $record;
            }
        }

        if ($userData === false) {
            return false;
        }

        return true;
    }

    public function auth(string $username, string $password)
    {
        /*$qb = new QueryBuilder();
        $userData = $this->database->execute(
            $qb->select('*', 'user', [
                'username' => $username,
                'password' => hash('ripemd160', $password)
            ])
        );*/

        $userData = false;

        $database = $this->getCSV();
        foreach ($database as $record) {
            if ($record['username'] == $username && $record['password'] == hash('ripemd160', $password)) {
                $userData = $record;
            }
        }

        if ($userData === false) {
            return false;
        }

        $userData['token'] = hash('ripemd160', date('U') . rand(100,999));

        /*$this->database->execute(
            $qb->update('user', [
                'username' => $username,
                'password' => hash('ripemd160', $password)
            ], $userData)
        );*/

        foreach ($database as $key => $record) {
            if ($record['username'] == $username && $record['password'] == hash('ripemd160', $password)) {
                $database[$key]['token'] = $userData['token'];
            }
        }

        $this->storeCSV($database);

        $user = new User();

        foreach ($userData as $key => $value)
        {
            $user->$key = $value;
        }

        $this->storeUser($user);

        return $user;
    }

    public function getCSV()
    {
        $labels = ['id','username','password','display_name','token','avatar'];
        $data = [];

        $row = 0;
        if (($handle = fopen(self::CSV_LOCATION, "r")) !== FALSE) {
            while (($record = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row == 0) {
                    $row++;
                    continue;
                }

                $labeledRecord = [];
                foreach ($record as $key => $value) {
                    $labeledRecord[$labels[$key]] = $value;
                }

                $data[$row] = $labeledRecord;

                $row++;
            }
            fclose($handle);
        }

        return $data;
    }

    public function storeCSV($data)
    {
        echo '<pre>';
        $fp = fopen(self::CSV_LOCATION, 'w');

        fputcsv($fp, ['id','username','password','display_name','token','avatar']);
        foreach ($data as $fields) {
            fputcsv($fp, array_values($fields));
        }

        fclose($fp);
    }

}