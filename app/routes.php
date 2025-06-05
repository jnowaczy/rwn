<?php

declare(strict_types=1);

use Slim\App;
use App\Application\Actions\StartAction;
use App\Application\Actions\User\PlayAction;
use App\Application\Actions\User\PrintAction;
use App\Application\Middleware\AdminMiddleware;
use App\Application\Actions\User\PlayDoneAction;
use App\Application\Actions\User\ImageUserAction;
use App\Application\Actions\Admin\AdminLoginAction;
use App\Application\Actions\Admin\AdminLogoutAction;
use App\Application\Actions\Admin\User\EditUserAction;
use App\Application\Actions\Admin\AdminDashboardAction;
use App\Application\Actions\Admin\User\ListUsersAction;
use App\Application\Actions\Admin\User\ScoreUserAction;
use App\Application\Actions\Admin\User\WriteUserAction;
use App\Application\Actions\Admin\AdminCommonDataAction;
use App\Application\Actions\Admin\User\DeleteUserAction;
use App\Application\Actions\Admin\User\EnvelopesAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
 //   $app->options('/{routes:.*}', function (Request $request, Response $response) {
 //       // CORS Pre-Flight OPTIONS Request Handler
 //       return $response;
 //   });

    $app->get('/', StartAction::class)->setName('start');

    
    $app->get('/{id}/play', PlayAction::class)->setName('users.play');
    $app->post('/{id}/play', PlayDoneAction::class)->setName('users.play.done');
    
    $app->get('/{id}/print', PrintAction::class)->setName('users.print');
    $app->get('/{id}/images/{imageId}', ImageUserAction::class)->setName('users.image');

    
    $app->map(['GET','POST'],'/admin/login', AdminLoginAction::class)->setName('admin.login');
    $app->get('/admin/logout', AdminLogoutAction::class)->setName('admin.logout');
    
    $app->group('/admin', function(Group $group){
        $group->get('[/]', AdminDashboardAction::class)->setName('admin.dashboard');
        $group->map(['GET','POST'],'/settings', AdminCommonDataAction::class)->setName('admin.commonData');

        $group->group('/users', function (Group $group) {
            $group->get('', ListUsersAction::class)->setName('admin.users.list');
            $group->get('/envelopes', EnvelopesAction::class)->setName('admin.users.envelopes');
            $group->get('/{id}/edit', EditUserAction::class)->setName('admin.users.edit');
            $group->post('/{id}/edit', WriteUserAction::class)->setName('admin.users.write');
            $group->map(['GET','POST'],'/{id}/score', ScoreUserAction::class)->setName('admin.users.score');
            $group->map(['GET','POST'],'/{id}/delete', DeleteUserAction::class)->setName('admin.users.delete');
        });
    })->addMiddleware(new AdminMiddleware('admin.login'));
};
