<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Tests\Jax\Unit;

use Necryin\Jax\Response;

/**
 * Class ResponseTest
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{

    public function testHeaderNormalize()
    {
        $response = new Response('', 200, ['content-type' => 'old']);

        $response->setHeader('CoNtent-TYpe', 'new');

        $this->assertEquals($response->getHeaders()['Content-Type'], 'new');
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testHeaderException()
    {
        new Response('', 200, ['content-type' => 1]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidStatusCode()
    {
        new Response('', 700);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidContent()
    {
        new Response(function(){}, 700);
    }

}
