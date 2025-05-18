<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin\User;

use Psr\Http\Message\ResponseInterface as Response;

class EditUserAction extends AdminUserAction
{
    protected function action(): Response
    {
        $userId = $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);

        $this->logger->info("User '{$user->getName()}' was viewed.");

        return $this->respondWithHtml('admin/users.edit.html.twig',['user'=>$user]);
    }
}
