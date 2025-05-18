<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin;

use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Action;
use App\Domain\Admin\AdminUser;

class AdminLoginAction extends Action
{
    protected function action(): Response
    {
        if($this->request->getMethod()==='POST'){
            $post=$this->getFormData();
            if(AdminUser::login($post['password']??'')){
                return $this->respondWithRedirect('admin.dashboard');
            }

            return $this->respondWithHtml('admin/login.html.twig',['error'=>'Niepoprawne hasło']);
        }

        return $this->respondWithHtml('admin/login.html.twig');
    }
}
