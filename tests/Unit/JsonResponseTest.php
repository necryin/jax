<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Tests\Jax\Unit;

use Necryin\Jax\JsonResponse;

/**
 * Class JsonResponseTest
 */
class JsonResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider jsonDataProvider
     */
    public function testNormal($data, $stringify)
    {
        $response = new JsonResponse($data);

        $this->assertEquals($response->getContent(), $stringify);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider jsonInvalidDataProvider
     */
    public function testSerializeException($data)
    {
        new JsonResponse($data);
    }

    public function jsonDataProvider()
    {
        return [
            [["id" => 1], "\"{\u0022id\u0022:1}\""],
        ];
    }

    public function jsonInvalidDataProvider()
    {
        return [
            ["\xB1\x31"],
            [1.8e308],
        ];
    }
}
