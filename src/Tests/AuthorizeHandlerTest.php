<?php

use App\Handlers\AuthorizeHandler;
use Bref\Context\Context;
use Firebase\JWT\JWT;
use PHPUnit\Framework\TestCase;

class AuthorizeHandlerTest extends TestCase
{
    public function test_authorize()
    {
        $token = JWT::encode([
            'username' => 'test',
        ], 'test');
        putenv('JWT_SECRET=test');

        $handler = new AuthorizeHandler();
        $response = $handler->handle(
            [
                'type' => 'TOKEN',
                'authorizationToken' => 'Bearer ' . $token,
                'methodArn' => 'test',
            ],
            new Context('', 0, '', '')
        );
    }
}