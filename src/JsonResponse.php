<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Jax;

/**
 * Class JsonResponse
 * @package Necryin\Jax
 */
class JsonResponse extends Response
{

    public $encodingOptions = 15; //15 === JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT

    /**
     * JsonResponse constructor.
     * @param mixed $data
     * @param int $status
     * @param array $headers
     */
    public function __construct($data = null, $status = 200, array $headers = [])
    {
        parent::__construct($this->setData($data), $status, $headers);
    }

    /**
     * @param mixed $data
     * @return string
     * @throws \InvalidArgumentException if json_encode() errors triggered
     */
    public function setData($data = [])
    {
        $data = json_encode($data, $this->encodingOptions);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function setGeneralHeaders()
    {
        $this->headers['Content-Type'] = 'application/json;charset=utf-8';
    }
    
    /**
     * {@inheritdoc}
     */
    public function setContent($data = null)
    {
        $this->content = $this->setData($data);
    }
}
