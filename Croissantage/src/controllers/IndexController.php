<?php
namespace Src\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class IndexController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        $this->logger->info("Index action dispatched");
        $db = $this->container->get('pdo');
        $tmp = $db->prepare("SELECT * FROM Student");
        $tmp->execute();
        $rcCfgs = $tmp->fetchAll();

        $this->view->render($response, 'adminLTE.phtml', ["name" => json_encode($rcCfgs)]);
        return $response;
    }
}