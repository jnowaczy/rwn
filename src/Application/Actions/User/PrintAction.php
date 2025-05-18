<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use chillerlan\QRCode\QRCode;
use Psr\Http\Message\ResponseInterface as Response;

class PrintAction extends UserAction
{
    protected function action(): Response
    {
        $id = $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($id);
        $this->logger->info("Users $id was printed.");

        $settings=$this->settings->get('rwn');
        assert(is_array($settings) && is_string($settings['baseUrl']));
        $baseUrl=$settings['baseUrl'];

        $qrLink=$baseUrl.$this->urlFor('users.play', ['id'=>$id]);
        $qrCode=(new QRCode())->render($qrLink);
        
        return $this->respondWithHtml('print.html.twig', ['user' => $user,'qrCode'=>$qrCode]);
    }



}
