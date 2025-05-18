<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class PlayAction extends UserAction
{
    protected function action(): Response
    {
        $id = $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($id);
        $this->logger->info("Users $id was viewed.");

        return $this->respondWithHtml('play.html.twig', ['user' => $user]);
    }



}
