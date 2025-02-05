<?php
namespace OffbeatWP\Routes;

use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\Routing\RouteCollection as SymfonyRouteCollection;

class RouteCollection extends SymfonyRouteCollection
{
    /** @param SymfonyRoute[] $routes */
    public function __construct (array $routes = [])
    {
        foreach ($routes as $name => $route) {
            $this->add($name, $route);
        }
    }

    public function removeAll(): self
    {
        foreach ($this->all() as $route) {
            $this->remove($route->getName());
        }

        return $this;
    }

    public function findByType(string $type): RouteCollection
    {
        return $this->where('type', $type);
    }

    /**
     * @param string $whereKey
     * @param class-string $whereValue
     * @return RouteCollection
     */
    public function where($whereKey, $whereValue): RouteCollection
    {
        $routes = array_filter($this->all(), static function ($route) use ($whereKey, $whereValue) {
            return $whereKey === 'type' && get_class($route) === $whereValue;
        });

        $filteredRouteCollection = new self($routes);

        if ($routes) {
            foreach ($routes as $routeName => $route) {
                $filteredRouteCollection->add($routeName, $route);
            }
        }

        return $filteredRouteCollection;
    }
}
