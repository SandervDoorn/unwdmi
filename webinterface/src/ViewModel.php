<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 14:17
 */

namespace App;

use App\Entity\User;

class ViewModel
{

    private $_data;

    public $content;

    public $user;

    public function __construct(array $data = [])
    {
        $this->_data = $data;

        if (isset($_SESSION['user']) && $_SESSION['user'] instanceof User) {
            $this->user = $_SESSION['user'];
        }
    }

    public function partial($template, $args = [])
    {
        $partial = new Renderer(new ViewModel($args));
        return $partial->render($template);
    }

    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    public function __set($property, $value)
    {
        $this->_data[$property] = $value;
    }

    public function __get($property)
    {
        return $this->_data[$property] ?? $this->$property;
    }

}