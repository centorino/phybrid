<?php
namespace Phybrid;

class PhybridRouter extends \Slim\Router
{
    public function __construct($originalRouter)
    {
        parent::__construct();
        $this->routes = $originalRouter->routes;
        $this->namedRoutes = $originalRouter->namedRoutes;
        $this->routeGroups = $originalRouter->routeGroups;
    }
    public function getMatchedRoutes($httpMethod, $resourceUri, $reload = false)
    {
        // Force a reload of all matched routes
        return parent::getMatchedRoutes($httpMethod, $resourceUri, true);
    }
}
