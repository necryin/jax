<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Jax\Controller;

use Necryin\Jax\Response;

/**
 * Class MainController
 * @package Necryin\Jax\Controller
 */
class MainController extends Controller
{

    /**
     * @return Response
     */
    public function actionIndex()
    {
        return new Response($this->simpleRender('index.html'));
    }
}
