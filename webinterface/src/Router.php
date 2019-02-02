<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 13:33
 */

namespace App;

class Router {

    /**
     * @var Route[]
     */
    private $routes = [];

    /**
     * @var Route
     * */
    private $notFoundRoute;


    public function build(array $config)
    {
        foreach ($config as $routeName => $routeConfig)
        {
            $this->routes[$routeName] = new Route($routeName, $routeConfig);
        }
    }

    public function route(Request $request): Route
    {
        foreach ($this->routes as $route) {
            /** @var $route Route */
            preg_match('/^' . str_replace('/', '\/', $route->getRoute()) . '$/', $request->getUrl(), $result);

            if (count($result)) {
                return $route;
            }
        }

        if (($route = $this->getRouteByName('not_found')) !== false) {
            return $route;
        }

        throw new \Exception('No route Found', 404);
    }

    public function getRouteByName(string $name)
    {
        if (isset($this->routes[$name])) {
            return $this->routes[$name];
        }

        return false;
    }

}