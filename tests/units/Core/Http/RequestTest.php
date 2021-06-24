<?php

require_once __DIR__.'/../../Base.php';

use Kanboard\Core\Http\Request;

class RequestTest extends Base
{
    public function testGetStringParam()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEquals('', $request->getStringParam('myvar'));

        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEquals('default', $request->getStringParam('myvar', 'default'));

        $request = new Request($this->container, array(), array('myvar' => 'myvalue'), array(), array(), array());
        $this->assertEquals('myvalue', $request->getStringParam('myvar'));
    }

    public function testGetIntegerParam()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEquals(0, $request->getIntegerParam('myvar'));

        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEquals(5, $request->getIntegerParam('myvar', 5));

        $request = new Request($this->container, array(), array('myvar' => 'myvalue'), array(), array(), array());
        $this->assertEquals(0, $request->getIntegerParam('myvar'));

        $request = new Request($this->container, array(), array('myvar' => '123'), array(), array(), array());
        $this->assertEquals(123, $request->getIntegerParam('myvar'));
    }

    public function testGetValues()
    {
        $request = new Request($this->container, array(), array(), array('myvar' => 'myvalue'), array(), array());
        $this->assertEmpty($request->getValue('myvar'));

        $request = new Request($this->container, array(), array(), array('myvar' => 'myvalue', 'csrf_token' => $this->container['token']->getCSRFToken()), array(), array());
        $this->assertEquals('myvalue', $request->getValue('myvar'));

        $request = new Request($this->container, array(), array(), array('myvar' => 'myvalue', 'csrf_token' => $this->container['token']->getCSRFToken()), array(), array());
        $this->assertEquals(array('myvar' => 'myvalue'), $request->getValues());

        $request = new Request($this->container, array(), array(), array('myvar' => 'myvalue', '-----------------------------7e1c32510025c--' => '', 'csrf_token' => $this->container['token']->getCSRFToken()), array(), array());
        $this->assertEquals(array('myvar' => 'myvalue'), $request->getValues());
    }

    public function testGetFileContent()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEmpty($request->getFileContent('myfile'));

        $filename = tempnam(sys_get_temp_dir(), 'UnitTest');
        file_put_contents($filename, 'something');

        $request = new Request($this->container, array(), array(), array(), array('myfile' => array('tmp_name' => $filename)), array());
        $this->assertEquals('something', $request->getFileContent('myfile'));

        unlink($filename);
    }

    public function testGetFilePath()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEmpty($request->getFilePath('myfile'));

        $request = new Request($this->container, array(), array(), array(), array('myfile' => array('tmp_name' => 'somewhere')), array());
        $this->assertEquals('somewhere', $request->getFilePath('myfile'));
    }

    public function testIsPost()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertFalse($request->isPost());

        $request = new Request($this->container, array('REQUEST_METHOD' => 'POST'), array(), array(), array(), array());
        $this->assertTrue($request->isPost());
    }

    public function testIsAjax()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertFalse($request->isAjax());

        $request = new Request($this->container, array('HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'), array(), array(), array(), array());
        $this->assertTrue($request->isAjax());
    }

    public function testIsHTTPS()
    {
        $request = new Request($this->container, array(), array(), array(), array());
        $this->assertFalse($request->isHTTPS());

        $request = new Request($this->container, array('HTTPS' => ''), array(), array(), array(), array());
        $this->assertFalse($request->isHTTPS());

        $request = new Request($this->container, array('HTTPS' => 'off'), array(), array(), array(), array());
        $this->assertFalse($request->isHTTPS());

        $request = new Request($this->container, array('HTTPS' => 'on'), array(), array(), array(), array());
        $this->assertTrue($request->isHTTPS());

        $request = new Request($this->container, array('HTTPS' => '1'), array(), array(), array(), array());
        $this->assertTrue($request->isHTTPS());

        $request = new Request($this->container, array('HTTP_X_FORWARDED_PROTO' => 'https'), array(), array(), array(), array());
        $this->assertTrue($request->isHTTPS());

        $request = new Request($this->container, array('HTTP_X_FORWARDED_PROTO' => 'http'), array(), array(), array(), array());
        $this->assertFalse($request->isHTTPS());
    }

    public function testGetCookie()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEmpty($request->getCookie('mycookie'));

        $request = new Request($this->container, array(), array(), array(), array(), array('mycookie' => 'miam'));
        $this->assertEquals('miam', $request->getCookie('mycookie'));
    }

    public function testGetHeader()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEmpty($request->getHeader('X-Forwarded-For'));

        $request = new Request($this->container, array('HTTP_X_FORWARDED_FOR' => 'test'), array(), array(), array(), array());
        $this->assertEquals('test', $request->getHeader('X-Forwarded-For'));
    }

    public function testGetRemoteUser()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEmpty($request->getRemoteUser());

        $request = new Request($this->container, array(REVERSE_PROXY_USER_HEADER => 'test'), array(), array(), array(), array());
        $this->assertEquals('test', $request->getRemoteUser());
    }

    public function testGetRemoteName()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEmpty($request->getRemoteName());

        $request = new Request($this->container, array(REVERSE_PROXY_NAME_HEADER => 'John Doe'), array(), array(), array(), array());
        $this->assertEquals('John Doe', $request->getRemoteName());
    }

    public function testGetRemoteEmail()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEmpty($request->getRemoteEmail());

        $request = new Request($this->container, array(REVERSE_PROXY_EMAIL_HEADER => 'test@example.com'), array(), array(), array(), array());
        $this->assertEquals('test@example.com', $request->getRemoteEmail());
    }

    public function testGetQueryString()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEmpty($request->getQueryString());

        $request = new Request($this->container, array('QUERY_STRING' => 'k=v'), array(), array(), array(), array());
        $this->assertEquals('k=v', $request->getQueryString());
    }

    public function testGetUri()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEmpty($request->getUri());

        $request = new Request($this->container, array('REQUEST_URI' => '/blah'), array(), array(), array(), array());
        $this->assertEquals('/blah', $request->getUri());
    }

    public function testGetUserAgent()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEquals('Unknown', $request->getUserAgent());

        $request = new Request($this->container, array('HTTP_USER_AGENT' => 'My browser'), array(), array(), array(), array());
        $this->assertEquals('My browser', $request->getUserAgent());
    }

    public function testGetIpAddress()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEquals('Unknown', $request->getIpAddress());

        $request = new Request($this->container, array('HTTP_X_REAL_IP' => '192.168.1.1,127.0.0.1'), array(), array(), array(), array());
        $this->assertEquals('192.168.1.1', $request->getIpAddress());

        $request = new Request($this->container, array('HTTP_X_FORWARDED_FOR' => '192.168.0.1,127.0.0.1'), array(), array(), array(), array());
        $this->assertEquals('192.168.0.1', $request->getIpAddress());

        $request = new Request($this->container, array('REMOTE_ADDR' => '192.168.0.1'), array(), array(), array(), array());
        $this->assertEquals('192.168.0.1', $request->getIpAddress());

        $request = new Request($this->container, array('REMOTE_ADDR' => ''), array(), array(), array(), array());
        $this->assertEquals('Unknown', $request->getIpAddress());
    }
}
