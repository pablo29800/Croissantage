<?php
namespace Src\Controller;

class BaseController
{
    protected $view;
    protected $logger;

    public function __construct($view, $logger, $container=null)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->container = $container;
    }
}