<?php

declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param string $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findUserOfId(string $id): User;
}
