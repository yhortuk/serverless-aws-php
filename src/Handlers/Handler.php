<?php declare(strict_types=1);

namespace App\Handlers;

use Pimple\Psr11\Container;
use Psr\Http\Server\RequestHandlerInterface;

abstract class Handler implements RequestHandlerInterface
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * UserCreateHandler constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
