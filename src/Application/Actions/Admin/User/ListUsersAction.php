<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin\User;

use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;

class ListUsersAction extends AdminUserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $users = $this->userRepository->findAll();
        $this->logger->info("Users list was viewed.");

        return $this->respondWithHtml('admin/users.list.html.twig', ['list'=>$users]);
    }
}
