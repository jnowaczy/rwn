<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin;

use App\Application\Actions\Action;
use App\Domain\Admin\AdminUser;
use Psr\Http\Message\ResponseInterface as Response;

class AdminLogoutAction extends Action
{
    protected function action(): Response
    {
        AdminUser::logout();
        return $this->respondWithRedirect('admin.login');
    }
}
