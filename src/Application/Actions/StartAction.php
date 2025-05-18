<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class StartAction extends Action
{
    protected function action(): Response
    {
     
        $this->logger->info("Start page was viewed.");

        return $this->respondWithHtml('start.html.twig');
    }



}
