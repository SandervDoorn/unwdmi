<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 14:40
 */

namespace App\Model;

use App\ViewModel;

class TestModel extends ViewModel
{

    public function testMe() {
        return 'Rick';
    }

}