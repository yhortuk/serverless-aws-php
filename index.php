<?php declare(strict_types=1);

$container = require __DIR__ . '/src/bootstrap.php';
$function = array_pop(explode('-', getenv('AWS_LAMBDA_FUNCTION_NAME')));
$handler = '\App\Handlers\\'.$function.'Handler';
/** @var App\Handlers\Handler $handler */
return new $handler($container);
