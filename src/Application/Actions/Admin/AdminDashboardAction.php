<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin;

use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Action;

class AdminDashboardAction extends Action
{
    protected function action(): Response
    {
        return $this->respondWithHtml('admin/dashboard.html.twig');
    }
}
