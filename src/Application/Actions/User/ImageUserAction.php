<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Slim\Psr7\Stream;
use Psr\Http\Message\ResponseInterface as Response;

class ImageUserAction extends UserAction
{
    protected function action(): Response
    {
        $userId = (string)$this->resolveArg('id');
        $imageId = (string)$this->resolveArg('imageId');
        $user = $this->userRepository->findUserOfId($userId);

        $image = $user->getImages()['image_'.$imageId] ?? [];
        if (!($image['src'] ?? null)) {
            $image['src'] = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'NoImage.png';
        }

        $ext=preg_replace('/.*\./','', $image['src']);

        $fh = fopen($image['src'], 'r');
        $mime = "image/$ext";//\mime_content_type($fh);
        $body = new Stream($fh);
        return $this->response->withHeader('Content-Type', $mime)->withBody($body);
    }
}
