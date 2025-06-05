<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin\User;

use Psr\Http\Message\ResponseInterface as Response;

class EnvelopesAction extends AdminUserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $users = $this->userRepository->findAll();
        $this->logger->info("envelopes was viewed.");

        return $this->respondWithHtml('admin/users.envelopes.html.twig', ['list'=>$users]);
    }
}
