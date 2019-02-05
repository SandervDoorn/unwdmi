<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 13:51
 */

namespace App;

class Renderer
{

    /**
     * @var ViewModel
     */
    private $_model;


    public function __construct(ViewModel $viewModel)
    {
        $this->_model = $viewModel;
    }

    public function render($template)
    {
        $template   = str_replace('\\', '/', $template);
        $file       = __DIR__ . '/../view/' . $template . '.phtml';

        if(!file_exists($file))
        {
            throw new \Exception(sprintf(
                'Template with name "%s" not found!',
                $template
            ));
        }

        try {
            ob_start();

            $includeReturn  = include $file;
            $content        = ob_get_clean();
        } catch (\Exception $ex) {
            ob_end_clean();
            throw $ex;
        }

        return $content;
    }

    public function __get($property)
    {
        return $this->_model->__get($property);
    }

    public function __set($property, $value)
    {
        $this->_model->__set($property, $value);
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->_model, $name], $arguments);
    }

}