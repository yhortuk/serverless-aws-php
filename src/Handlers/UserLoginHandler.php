<?php declare(strict_types=1);

namespace App\Handlers;

use Aws\DynamoDb\DynamoDbClient;;

use Aws\DynamoDb\Marshaler;
use Firebase\JWT\JWT;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserLoginHandler extends Handler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents());
        $marshaler = new Marshaler();
        $queryUserParams = [
            'TableName' =>  getenv('DYNAMODB_USER_TABLE'),
            'KeyConditionExpression' => '#username = :username',
            'ExpressionAttributeNames' => [
                '#username' => 'pk',
            ],
            'ExpressionAttributeValues' => [
                ':username' => $marshaler->marshalValue($body->username),
            ],
        ];

        /** @var DynamoDbClient $database */
        $database = $this->container->get('database');
        $userExists = $database->query($queryUserParams);

        if (!$userExists['Items']) {
            return new Response(404);
        }

        $user = $marshaler->unmarshalItem($userExists['Items'][0], true);
        if (!password_verify($body->password, $user->password)) {
            return new Response(404);
        }

        $jwt = JWT::encode(['username' => $user->pk,], getenv('JWT_SECRET'));
        return new Response(200, ['Content-Type' => 'application/json'], json_encode(['token' => $jwt]));
    }
}
