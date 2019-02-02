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

    public static $scripts = [];

    public function sideMenu($args = [])
    {
        return $this->partial('layout/side-menu', $args);
    }

    public function header()
    {
        return $this->partial('layout/header');
    }

    public static function appendScript(string $link, $key = 0, $type = false)
    {
        if ($type) {
            self::$scripts[$key][] = '<script src="' . $link . '" type="' . $type . '"></script>';
        } else {
            self::$scripts[$key][] = '<script src="' . $link . '"></script>';
        }
    }

    public function scripts()
    {
        $html = '';
        ksort(self::$scripts);

        foreach (self::$scripts as $prio => $scripts) {
            foreach ($scripts as $script) {
                $html .= $script;
            }
        }

        return $html;
    }

}