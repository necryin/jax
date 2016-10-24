<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Jax;

use Necryin\Jax\Exception\ComponentNotFoundException;

/**
 * Class Application
 * @package Necryin\Jax\Core
 */
class Application
{
    /**
     * @var array
     */
    private static $components = [];
    
    /**
     * Application constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        ErrorHandler::register();
        
        foreach ($config as $component => $componentArgs) {
            $class = $config[$component]['class'];
            unset($config[$component]['class']);
            $args = $config[$component];

            $reflection = new \ReflectionClass($class);
            self::$components[$component] = $reflection->newInstanceArgs($args);
        }
    }

    /**
     * @param string $alias
     * @return mixed
     * @throws ComponentNotFoundException
     */
    public static function getComponent($alias)
    {
        if(array_key_exists($alias, self::$components)) {
            return self::$components[$alias];
        }
        
        throw new ComponentNotFoundException();
    }

    /**
     * @return string
     */
    public static function getRootDirectory()
    {
        return __DIR__;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handleRequest(Request $request)
    {
        /** @var SimpleUrlMather $router */
        $router = $this->getComponent(Component::ROUTER);
        list($controller, $action) = $router->match($request);
        
        return $controller->$action($request);
    }
}
