<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin\User;

use App\Domain\User\User;
use App\Domain\User\CommonData;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\ResponseInterface as Response;

class WriteUserAction extends AdminUserAction
{
    protected function action(): Response
    {

        $commonData=new CommonData;

        $userId = $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);
        $form = $this->getFormData();
        $data = $user->getData();
        $data['name'] = trim($form['name']);
        $data['section'] = trim($form['section']);
        $data['info'] = trim($form['info']);
        $data['author'] = trim($form['author']);
        $data['status'] = trim($form['status']);
        $data['manifest'] = trim($form['manifest']);

        $uploaded = $this->request->getUploadedFiles();

        for ($i = 1; $i <= $commonData->getFotoCount(); ++$i) {
            $data['images'][$i]['id'] = $i;
            $data['images'][$i]['solution'] = $form['image_' . $i . '_solution']??'';

            $file = $uploaded['image_' . $i . '_file'] ?? null;
            if ($file instanceof UploadedFileInterface && $file->getSize()) {
                $data['images'][$i]['src'] = $user->addImage($uploaded['image_' . $i . '_file']);
            }elseif($form['image_' . $i . '_delete']??''){
                unset($data['images'][$i]['src']);
            }
        }
        $user->setData($data);
        return $this->respondWithRedirect('admin.users.list');
    }
}
