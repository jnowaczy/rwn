<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use DirectoryIterator;

class InMemoryUserRepository implements UserRepository
{
    /**
     * @var array<string,User>
     */
    private array $users = [];
    private string $dataPath;


    public function __construct(?string $dataPath = null)
    {
        $this->dataPath = $dataPath ?? implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 4), 'var', 'data', 'teams']);

        foreach (new DirectoryIterator($this->dataPath) as $item) {
            if ($item->isDir() && !$item->isDot() && $item->getExtension()!='deleted') {
                $user = new User($item->getFilename(), $item->getPathname());
                $this->users[$user->getId()] = $user;
            }
        }
    }

    public function findAll(): array
    {
        return array_values($this->users);
    }

    public function findUserOfId(string $id): User
    {
        if ($id === 'new') {
            $id = md5(random_bytes(250));
            return new User($id, $this->dataPath . DIRECTORY_SEPARATOR . $id);
        }

        if (!isset($this->users[$id])) {
            throw new UserNotFoundException();
        }

        return $this->users[$id];
    }
}
