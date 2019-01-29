<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 13:38
 */

namespace App;

class Request
{

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $get;

    /**
     * @var array
     */
    private $post;


    public function __construct(string $url, array $get = [], array $post = [])
    {
        $this->url  = $url;
        $this->get  = $get;
        $this->post = $post;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function getGet(): array
    {
        return $this->get;
    }

    /**
     * @param mixed $get
     */
    public function setGet($get)
    {
        $this->get = $get;
    }

    public function isGet(): bool
    {
        return count($this->get) > 0;
    }

    /**
     * @return array
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    public function isPost(): bool
    {
        return count($this->post) > 0;
    }

}