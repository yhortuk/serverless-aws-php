<?php declare(strict_types=1);

namespace App\Handlers;

use App\Events\AuthorizeEvent;
use Bref\Context\Context;
use Bref\Event\Handler as EventHandler;
use Firebase\JWT\JWT;

class AuthorizeHandler implements EventHandler
{
    public function handle($event, Context $context)
    {
        $event = new AuthorizeEvent($event);

        try {
            $token = JWT::decode($event->getBearerAuthorizationToken(), getenv('JWT_SECRET'), ['HS256']);
            return $this->policy($token->username, 'Allow', $event->getMethodArn());
        } catch (\Exception $e) {}

        return $this->policy('undefined', 'Deny', $event->getMethodArn());
    }

    private function policy($principalId, $effect, $resource)
    {
        return [
            'principalId' => $principalId,
            'policyDocument' => [
                "Version" => "2012-10-17",
                "Statement" => [
                    [
                        'Action' => 'execute-api:Invoke',
                        'Effect' => $effect,
                        'Resource' => $resource,
                    ]
                ]
            ]
        ];
    }


}
