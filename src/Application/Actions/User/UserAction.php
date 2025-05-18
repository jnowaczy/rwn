<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Domain\User\UserRepository;
use App\Application\Settings\SettingsInterface;

abstract class UserAction extends Action
{

    public function __construct(LoggerInterface $logger, protected UserRepository $userRepository, protected SettingsInterface $settings)
    {
        parent::__construct($logger);
    }


}
