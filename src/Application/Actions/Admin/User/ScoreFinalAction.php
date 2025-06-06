<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin\User;

use App\Domain\User\CommonData;
use Psr\Http\Message\ResponseInterface as Response;

class ScoreFinalAction extends AdminUserAction
{
    protected function action(): Response
    {
        $finalScore=[];
        $users = $this->userRepository->findAll();

        $commonData=new CommonData();

        foreach($users as $user){
            $name=$user->getName();
            $finalScore[$name]['sections']??=0;
            $finalScore[$name]['sections']++;
            foreach($user->getScore() as $key=>$value){
                $type=preg_replace('/_\d+$/','',$key);
                $num=preg_replace('/\D+/','',$key);
               

                $finalScore[$name][$type]['count']??=0;
                $finalScore[$name][$type]['count']++;
                

                $finalScore[$name][$type]['points']??=0;
                if(is_numeric($value) && is_numeric($finalScore[$name][$type]['points'])){
                    $finalScore[$name][$type]['points']+=$value;
                }else{
                    $finalScore[$name][$type]['points']=($value==='')?'Ocena niekompletna':'Błąd';
                }
            }

        }

        return $this->respondWithHtml('admin/users.finalScore.html.twig',['finalScore'=>$finalScore]);
    }
}
