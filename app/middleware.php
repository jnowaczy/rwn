<?php

declare(strict_types=1);

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use App\Domain\Admin\AdminUser;
use App\Domain\User\CommonData;
use App\Application\Middleware\SessionMiddleware;

return function (App $app) {
    $app->add(SessionMiddleware::class);

    $twig = Twig::create(__DIR__ . '/../templates', [
        'cache' => false,
        'strict_variables'=>true,
        'debug' => true,
    ]);
    $twig->offsetSet('adminUser', AdminUser::current());
    $twig->offsetSet('commonData', new CommonData);

    $twig->addExtension(new \Twig\Extension\DebugExtension());
    $app->add(TwigMiddleware::create($app, $twig));
};
