<?php declare(strict_types=1);

namespace App\Events;

use Bref\Event\LambdaEvent;

class AuthorizeEvent implements LambdaEvent
{
    /**
     * @var array
     */
    private $event;

    public function __construct(array $event)
    {
        $this->event = $event;
    }

    public function getType(): string
    {
        return $this->event['type'];
    }

    public function getAuthorizationToken(): string
    {
        return $this->event['authorizationToken'];
    }

    public function getBearerAuthorizationToken(): string
    {
        list($type, $token) = explode(' ', $this->getAuthorizationToken());
        if ($type !== 'Bearer') {
            throw new \InvalidArgumentException("Expected token type Bearer, but got $type");
        }
        return $token;
    }

    public function getMethodArn(): string
    {
        return $this->event['methodArn'];
    }

    public function toArray(): array
    {
        return $this->event;
    }
}