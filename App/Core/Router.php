<?php
namespace App\Core;

class Router//uma das classes mais importantes do projeto, ela monta as rotas do projeto, e redireciona para o controller correto, e chama o método correto do controller, e passa os parâmetros corretos para o método do controller, e chama a view correta, e passa os parâmetros corretos para a view, e chama o model correto, e passa os parâmetros corretos para o model.

{
    private array $routes = [];

    public function get(?string $route, ?string $action)
    {

        $this->routes[] = [
            'method' => 'get',
            'route'  => $route,
            'action' => $action,
        ];
    }

    public function post(?string $route, ?string $action)
    {

        $this->routes[] = [
            'method' => 'post',
            'route'  => $route,
            'action' => $action,
        ];
    }

    public function run()
    {
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        $projetoPath = parse_url(URL_BASE, PHP_URL_PATH) ?? '';

        if ($projetoPath !== '') {
            $uri = str_replace($projetoPath, '', $uri);
        }


        foreach ($this->routes as $route) {
            if ($route['route'] == $uri && $route['method'] == $method) {
                return $this->dispatch($route);
            }
        }

        http_response_code(404);
        exit('Rota não encontrada');
    }

    public function dispatch(?array $route)
    {

        list($controller, $method) = explode('@', $route['action']);

        $controllerClass = "App\\Controllers\\$controller";

        if (! class_exists($controllerClass)) {
            print "Controller $controller não encontrado";
            die;
        }

        if (! method_exists($controllerClass, $method)) {
            print "Método $method não encontrado em $controllerClass";
            die;
        }

        $controller = new $controllerClass;
        $controller->$method();

    }

    public function getAllRoutes()
    {
        return $this->routes;
    }

}
