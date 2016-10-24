<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Jax;

/**
 * Class Request
 * @package Necryin\Jax
 */
class Request
{
    /**
     * Request body parameters ($_POST).
     */
    protected $post;

    /**
     * Query string parameters ($_GET).
     */
    protected $query;

    /**
     * Server and execution environment parameters ($_SERVER).
     */
    protected $server;
    
    /**
     * Parsed headers
     */
    protected $headers;

    /**
     * Request constructor.
     * @param array $query
     * @param array $post
     * @param array $server
     */
    public function __construct(array $query = [], array $post = [], array $server = [])
    {
        $this->query = $query;
        $this->post = $post;
        $this->server = $server;
        $this->headers = $this->extractHeaders();
    }

    /**
     * @return Request
     */
    public static function createFromGlobals()
    {
        return new self($_GET, $_POST, $_SERVER);
    }
    
    /**
     * @return array
     */
    private function extractHeaders()
    {
        $headers = [];
        $contentHeaders = ['CONTENT_LENGTH' => true, 'CONTENT_MD5' => true, 'CONTENT_TYPE' => true];

        foreach ($this->server as $key => $value) {
            if (0 === strpos($key, 'HTTP_')) {
                $headers[substr($key, 5)] = $value;
            } elseif (isset($contentHeaders[$key])) {
                $headers[$key] = $value;
            }
        }

        return $headers;
    }

    /**
     * @param string $var
     * @param mixed $default
     * @return mixed
     */
    public function getPost($var, $default = null)
    {
        return $this->getVar('post', $var, $default);
    }

    /**
     * @param string $var
     * @param mixed $default
     * @return mixed
     */
    public function getQuery($var, $default  = null)
    {
        return $this->getVar('query', $var, $default);
    }

    /**
     * @param string $var
     * @param mixed $default
     * @return mixed
     */
    public function getServer($var, $default = null)
    {
        return $this->getVar('server', $var, $default);
    }

    /**
     * @param string $var
     * @param mixed $default
     * @return mixed
     */
    public function getHeaders($var, $default = null)
    {
        return $this->getVar('headers', $var, $default);
    }

    /**
     * @param string $container [post, query, server, headers]
     * @param string $var
     * @param mixed $default
     * @return mixed
     */
    private function getVar($container, $var, $default)
    {
        $bag = $this->$container;
        return array_key_exists($var, $bag) ? $bag[$var] : $default;
    }
}
