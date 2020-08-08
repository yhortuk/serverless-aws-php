<?php

use Aws\Sdk;
use Pimple\Psr11\Container;

$container = new Pimple\Container();
$container->offsetSet('aws', function () {
    return new Sdk(
        [
            'version' => 'latest',
            'region' => $_ENV['AWS_DEFAULT_REGION']
        ]
    );
});
$container->offsetSet('database', function () use ($container) {
    return $container['aws']->createDynamoDB();
});

return new Container($container);