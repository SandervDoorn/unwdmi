<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 14:17
 */

namespace App;

class LayoutModel extends ViewModel
{

    public function sideMenu($args = [])
    {
        return $this->partial('layout/side-menu', $args);
    }

    public function header()
    {
        return $this->partial('layout/header');
    }

}