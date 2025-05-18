<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Slim\Routing\RouteContext;
use App\Domain\Admin\AdminUser;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Psr7Response;

class AdminMiddleware implements Middleware
{
    public function __construct(protected $loginRoute='admin.login')
    {
        
    }
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $adminUser=AdminUser::current();
        if($adminUser){
            return $handler->handle(($request->withAttribute('adminUser', $adminUser)));
        }
       
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        return (new Psr7Response(302))->withHeader('Location', $routeParser->urlFor($this->loginRoute));
    }

}
