<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin\User;

use App\Domain\User\CommonData;
use Psr\Http\Message\ResponseInterface as Response;

class ScoreUserAction extends AdminUserAction
{
    protected function action(): Response
    {
        $id = $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($id);
        $this->logger->info("Users $id was scored.");

        if($this->request->getMethod()==='POST'){
            $data=$user->getData();
            $data['totalScore']=0;

            $data['score']=$this->getFormData();
            foreach($data['score'] as &$value){
                $value=str_replace([' ','   ', ','],['','', '.'], $value);
                if($value==='')continue;
                if(is_numeric($data['totalScore']) && is_numeric($value)){
                    $data['totalScore']+=$value;
                }else{
                    $data['totalScore']='błąd';
                }
            }
            $user->setData($data);
            return $this->respondWithRedirect('admin.users.list');
        }

        return $this->respondWithHtml('admin/users.score.html.twig',['user'=>$user]);
    }
}
