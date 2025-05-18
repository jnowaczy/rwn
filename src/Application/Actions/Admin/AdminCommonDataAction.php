<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin;

use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Action;
use App\Domain\User\CommonData;

class AdminCommonDataAction extends Action
{
    protected function action(): Response
    {
        $commonData=new CommonData;
        if($this->request->getMethod()==='POST'){
            $post=$this->getFormData();
            $data=$commonData->getData();

            $data['title']=trim($post['title']);
            $data['creator']=trim($post['creator']);
            $data['manifestHeader']=trim($post['manifestHeader']);
            $data['manifestFooter']=trim($post['manifestFooter']);
            $data['additionalTasks']=[];
            foreach(explode("\n",$post['additionalTasks']) as $task){
                $task=trim($task);
                if($task!==''){
                    $data['additionalTasks'][]=$task;
                }
            }
            $data['fotoCount']=(int)trim($post['fotoCount']);
            

            if($post['password']??''){
                $data['passwordHash']=CommonData::hashPassword($post['password']);
            }
            $commonData->setData($data);
            return $this->respondWithRedirect('admin.dashboard');
        }
        return $this->respondWithHtml('admin/commonData.html.twig',['commonData'=>$commonData]);
    }


}
