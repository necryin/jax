<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Jax;

/**
 * Class Response
 * @package Necryin\Jax
 */
class Response
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_SERVICE_UNAVAILABLE = 503;
    const HTTP_GATEWAY_TIMEOUT = 504;

    /**
     * @var array
     */
    public static $statusTexts = [
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
    ];

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * Response constructor.
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct($content = '', $status = 200, array $headers = [])
    {
        $this->headers = $this->prepareHeaders($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
    }

    /**
     * @return void
     */
    public function sendHeaders()
    {
        if (headers_sent()) {
            return;
        }

        $this->setGeneralHeaders();
        
        // headers
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value, false, $this->statusCode);
        }

        // status
        header(sprintf('HTTP/%s %s %s', '1.0', $this->statusCode, self::$statusTexts[$this->statusCode]), true, $this->statusCode);
    }

    /**
     * @return void
     */
    public function sendContent()
    {
        echo $this->content;
    }

    /**
     * @return void
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    
    /**
     * @param string $name
     * @param string $value
     */
    public function setHeader($name, $value)
    {
        if(!is_string($name) || !is_string($value)) {
            throw new \UnexpectedValueException('The headers key and value should be string');
        }
        $this->headers[$this->normalizeHeaderName($name)] = $value;
    }

    /**
     * @param mixed $content
     * @return void
     * @throws \UnexpectedValueException When content cannot be cast to string
     */
    public function setContent($content)
    {
        if (null !== $content && !is_string($content) && !is_numeric($content) && !is_callable([$content, '__toString'])) {
            throw new \UnexpectedValueException(sprintf('The Response content must be a string or object implementing __toString(), "%s" given.', gettype($content)));
        }

        $this->content = (string) $content;
    }

    /**
     * @return string Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param int $code HTTP status code
     * @return Response
     * @throws \InvalidArgumentException When the HTTP status code is not valid
     */
    public function setStatusCode($code)
    {
        $this->statusCode = (int)$code;

        if ($this->isInvalid()) {
            throw new \InvalidArgumentException(sprintf('The HTTP status code "%s" is not valid.', $code));
        }
    }

    /**
     * @return int Status code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return bool
     */
    public function isInvalid()
    {
        return $this->statusCode < 100 || $this->statusCode >= 600;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function normalizeHeaderName($name)
    {
        return implode('-', array_map('ucfirst', array_map('strtolower', explode('-', $name))));
    }

    /**
     * @param $headers
     * @return array
     */
    protected function prepareHeaders($headers)
    {
        $result = [];
        foreach ($headers as $name => $value) {
            if(!is_string($name) || !is_string($value)) {
                throw new \UnexpectedValueException('The headers key and value should be string');
            }
            $result[$this->normalizeHeaderName($name)] = $value;
        }

        return $result;
    }

    /**
     * @return void
     */
    protected function setGeneralHeaders()
    {
        $this->headers['Content-Type'] = 'text/html;charset=utf-8';
    }
}
