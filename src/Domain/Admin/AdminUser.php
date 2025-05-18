<?php

declare(strict_types=1);

namespace App\Domain\Admin;

use App\Domain\User\CommonData;

class AdminUser
{
    final protected function __construct()
    {
    }
    
    public static function current(): ?static
    {
        if(isset($_COOKIE['PHPSESSID']) && session_status()!=PHP_SESSION_ACTIVE){
            session_start();
        }
        if($_SESSION['AdminUser']??null){
            return new static;
        }
        return null;
    }

    public static function login(string $password):?static
    {
        if(static::checkPassword($password)){
            if(session_status()!=PHP_SESSION_ACTIVE){
                session_start();
            }
            $_SESSION['AdminUser']='admin';
            return new static;
        }
        return null;
    }

    public static function logout():void
    {
        if(isset($_COOKIE['PHPSESSID'])){
            if(session_status()!=PHP_SESSION_ACTIVE){
                session_start();
            }
            session_destroy();
        }
    }

    private static function checkPassword(string $password):bool{

        $commonData=new CommonData;
        return $commonData->getPasswordHash()===CommonData::hashPassword($password);
    }
}
