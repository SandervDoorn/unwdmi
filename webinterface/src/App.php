<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 13:22
 */

namespace App;

use App\Controller\AuthController;
use App\Controller\ErrorController;
use App\Controller\IndexController;
use App\Entity\User;

class App {

    /**
     * @var Router
     */
    private $router;

    private $allowedControllers = [
        IndexController::class,
        AuthController::class,
        ErrorController::class,
    ];

    public function __construct(array $appConfig)
    {
        $this->buildRoutes($appConfig['router']);
    }

    public function buildRoutes(array $config) {
        $this->router = new Router();

        $this->router->build($config['routes']);
    }

    public function run()
    {
        $request = new Request($_SERVER['REQUEST_URI'], $_GET, $_POST);

        $route = $this->router->route($request);

        $controller = $route->getController();

        if(class_exists($controller) === false) {
            new \Exception(sprintf(
                'Controller of route "%s" does not exists!',
                $route->getName()
            ), 500);
        }

        // restrictions

        if (! in_array($controller, $this->allowedControllers)) {
            if (!isset($_SESSION['user']) || !$_SESSION['user'] instanceof User) {
                $route = $this->router->getRouteByName('not_allowed');

                $controller = $route->getController();

                if(class_exists($controller) === false) {
                    new \Exception(sprintf(
                        'Controller of route "%s" does not exists!',
                        $route->getName()
                    ), 500);
                }
            }
        }

        // end

        $controller = new $controller();
        $action     = $route->getAction();

        if(method_exists($controller, $action) === false) {
            new \Exception(sprintf(
                'Controller of route "%s" has no action "%s"!',
                $route->getName(),
                $route->getAction()
            ), 500);
        }

        $response = new Response();
        $viewModel = $controller->$action($request, $response);

        if (! $viewModel instanceof Response) {
            if (! $viewModel instanceof ViewModel) {
                if (is_array($viewModel)) {
                    $viewModel = new ViewModel($viewModel);
                } elseif ($viewModel === null) {
                    $viewModel = new ViewModel([]);
                } else {
                    throw new \Exception('There must be a ViewModel returned in the controller!');
                }
            }

            $viewRenderer = new Renderer($viewModel);
            $view = $viewRenderer->render($route->getTemplate());

            $layoutRenderer = new Renderer(new LayoutModel([
                'content' => $view
            ]));
            $layout = $layoutRenderer->render($route->getLayout());

            $response->setContent($layout);
        } else {
            $response = $viewModel;
        }

        foreach ($response->getHeaders() as $key => $value) {
            header($key . ': ' . $value);
        }

        return $response->getContent();
    }

}