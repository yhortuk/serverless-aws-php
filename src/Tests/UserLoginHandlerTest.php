<?php

use PHPUnit\Framework\TestCase;

class HelloHandlerTest extends TestCase
{
    public function test_hello_function()
    {
        $pimple = new \Pimple\Container();
        $pimple['database'] = $this->getMockBuilder(\Aws\DynamoDb\DynamoDbClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $handler = new \App\Handlers\UserCreateHandler(new \Pimple\Psr11\Container($pimple));

        $request =  (new \GuzzleHttp\Psr7\ServerRequest('post', '/'))
            ->withBody(\GuzzleHttp\Psr7\stream_for(json_encode(['username' => 'test', 'password' => 'test'])));

        $response = $handler->handle($request);

        $this->assertJsonStringEqualsJsonString(
            '{"status": "success"}',
            $response->getBody()->getContents()
        );
    }
}