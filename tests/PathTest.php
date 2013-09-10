<?php

use Paliari\Oauth2\Path;

class PathTest extends PHPUnit_Framework_Testcase
{
    public function testEmptyPath()
    {
        $path = new Path();
        $this->assertEquals('', $path);
    }

    public function testSimplePath()
    {
        $path = new Path(array('REQUEST_URI' => '/token'));
        $this->assertEquals('token', $path);
    }

    public function testTrimPath()
    {
        $path = new Path(array('REQUEST_URI' => '/token/'));
        $this->assertEquals('token', $path);
    }

    public function testMultiplePath()
    {
        $path = new Path(array('REQUEST_URI' => '/token/expire'));
        $this->assertEquals('token/expire', $path);
    }

    public function testBasedir()
    {
        $path = new Path(array('REQUEST_URI' => '/oauth2/token', 'SCRIPT_NAME' => '/oauth2/index.php'));
        $this->assertEquals('token', $path);
    }

    public function testDoubleBasedirDoublePath()
    {
        $path = new Path(array('REQUEST_URI' => '/api/oauth2/token/expire', 'SCRIPT_NAME' => '/api/oauth2/index.php'));
        $this->assertEquals('token/expire', $path);
    }
}
