<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Jax\Controller;

use Necryin\Jax\Application;
use Necryin\Jax\Component;
use Necryin\Jax\JsonResponse;
use Necryin\Jax\Model\Comment;
use Necryin\Jax\Request;

/**
 * Class ApiController
 * @package Necryin\Jax\Controller
 */
class ApiController extends Controller
{
    /**
     * @return array
     */
    public function actionRoots()
    {
        return new JsonResponse($this->getModel()->getRoots());
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function actionTree(Request $request)
    {
        $id = $request->getQuery('root_id');
        if($id === null) {
            return new JsonResponse(['error' => true], JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($this->getModel()->getTree($id));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function actionInsert(Request $request)
    {
        $id   = $request->getPost('parent_id');
        $text = $request->getPost('text');

        if($text === null) {
            return new JsonResponse(['error' => true], JsonResponse::HTTP_BAD_REQUEST);
        }

        $created = $this->getModel()->create($text, $id);
        if($created !== false) {
            return new JsonResponse($created, JsonResponse::HTTP_CREATED);
        }

        return new JsonResponse(['error' => true], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function actionUpdate(Request $request)
    {
        $id   = $request->getPost('id');
        $text = $request->getPost('text');

        if($id === null || $text === null) {
            return new JsonResponse(['error' => true], JsonResponse::HTTP_BAD_REQUEST);
        }

        $updated = $this->getModel()->update($text, $id);
        return new JsonResponse(['error' => !$updated], $updated ? JsonResponse::HTTP_OK : JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function actionDelete(Request $request)
    {
        $id = $request->getPost('id');

        if($id === null) {
            return new JsonResponse(['error' => true], JsonResponse::HTTP_BAD_REQUEST);
        }

        $deleted = $this->getModel()->delete($id);
        return new JsonResponse(['error' => !$deleted], $deleted ? JsonResponse::HTTP_OK : JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @return Comment
     */
    private function getModel()
    {
        return new Comment(Application::getComponent(Component::DB));
    }
}
