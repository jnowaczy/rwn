<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class PlayDoneAction extends UserAction
{
    protected function action(): Response
    {
        $id = $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($id);
        $this->logger->info("User $id - done");

        if($user->getUserSolution()){
            return $this->respondWithData(['class'=>'danger','msg'=>'Błąd: dokument został już wcześniej przesłany.']);
        }

        $data=[];
        foreach($this->getFormData() as $key=>$value){
            $key=str_replace("_$id",'',$key);
            $data[$key]=trim((string)$value);
        }
        $user->saveUserSolution($data);

        return $this->respondWithData(['class'=>'success','msg'=>'Dokument został pomyślnie przesłany. Dziękujemy.']);

    }



}
