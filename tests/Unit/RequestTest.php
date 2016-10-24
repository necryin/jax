<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Tests\Jax\Unit;


use Necryin\Jax\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testRequest()
    {
        $request = new Request(['a' => 'a'], ['b' => 'b'], ['c' => 'c', 'HTTP_NOT' => 'not']);

        $this->assertEquals($request->getQuery('a'), 'a');
        $this->assertEquals($request->getPost('b'), 'b');
        $this->assertEquals($request->getServer('c'), 'c');
        $this->assertEquals($request->getHeaders('NOT'), 'not');
    }
}
