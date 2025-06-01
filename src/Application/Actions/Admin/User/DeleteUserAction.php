<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin\User;

use Psr\Http\Message\ResponseInterface as Response;

class DeleteUserAction extends AdminUserAction
{
    protected function action(): Response
    {
        $userId = $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);

        if($this->request->getMethod()==='POST'){
            $user->deleteDataDir();
            return $this->respondWithRedirect('admin.users.list');
        }

        return $this->respondWithHtml('admin/users.delete.html.twig',['user'=>$user]);
    }
}
