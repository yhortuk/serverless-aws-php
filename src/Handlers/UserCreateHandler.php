<?php declare(strict_types=1);

namespace App\Handlers;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function GuzzleHttp\Psr7\stream_for;

class UserCreateHandler extends Handler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents());
        $this->container->get('database')
            ->putItem(
                [
                    'TableName' => getenv('DYNAMODB_USER_TABLE'),
                    'Item' => [
                        'pk' => ['S' => $body->username],
                        'password' => ['S' => password_hash($body->password,PASSWORD_BCRYPT)],
                    ]
                ]
            );

        return (new Response)
            ->withBody(stream_for(json_encode(['status' => 'success'])))
            ->withHeader('Content-Type', 'application/json');
    }
}
