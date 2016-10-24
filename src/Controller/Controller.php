<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Jax\Controller;

use Necryin\Jax\Application;

/**
 * Class Controller
 * @package Necryin\Jax\Controller
 */
abstract class Controller
{

    /**
     * @param string $view
     * @return mixed
     */
    public function simpleRender($view)
    {
        $path = $this->getView($view);
        return file_get_contents($path);
    }

    /**
     * @param string $view
     * @return string
     */
    protected function getView($view)
    {
        $path = Application::getRootDirectory() . '/Resources/' . $view;
        if(!is_file($path)) {
            throw new \UnexpectedValueException('View file not found');
        }
        
        return $path;
    }
}
