<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Jax;

/**
 * Class SimpleUrlMather
 * @package Necryin\Jax
 */
class SimpleUrlMather
{

    /**
     * @var string
     */
    private $routeKey = 'r';
    /**
     * @var string
     */
    private $fallback;

    /**
     * SimpleUrlMather constructor.
     * @param string $fallback
     */
    public function __construct($fallback)
    {
        $this->fallback = $fallback;
    }

    /**
     * @param Request $request
     * @return array [ControllerObject, ControllerMethod]
     */
    public function match(Request $request)
    {
        $matchString = $request->getQuery($this->routeKey);

        if($matchString === null) {
            $matchString = $this->fallback;
        }

        list($controllerClass, $actionString) = $this->extract($matchString);

        if(class_exists($controllerClass)) {
            $controllerObject = new $controllerClass();
            if(method_exists($controllerObject, $actionString)) {
                return [$controllerObject, $actionString];
            }
        }

        list($controllerFallClass, $actionFallString) = $this->extract($this->fallback);

        return [new $controllerFallClass(), $actionFallString];
    }

    /**
     * @param string $match
     * @return array [ControllerClass, ControllerMethod]
     */
    protected function extract($match)
    {
        list($controller, $action) = explode('/', $match, 2);
        $controllerClass = "Necryin\\Jax\\Controller\\" . ucfirst(strtolower($controller)) . "Controller";
        $actionString = "action" . ucfirst(strtolower($action));

        return [$controllerClass, $actionString];
    }
}
