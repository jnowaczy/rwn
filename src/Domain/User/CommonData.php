<?php

declare(strict_types=1);

namespace App\Domain\User;

class CommonData
{
    protected string $dataFilePath;
    protected static ?array $data = null;

    public function __construct(protected ?string $dataPath = null)
    {
        $dataPath = $dataPath ?? implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 3), 'var', 'data']);
        $this->dataFilePath = $dataPath . DIRECTORY_SEPARATOR . 'common.json';
    }

    public function getData(): array
    {
        if (static::$data) {
            return static::$data;
        }
        if (is_file($this->dataFilePath)) {
            $data = file_get_contents($this->dataFilePath);
            static::$data = json_decode($data, true);
        } else {
            static::$data = [];
        }
        return static::$data;
    }

    public function setData(array $data): void
    {
        static::$data = $data;
        $contents = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        if (!file_put_contents($this->dataFilePath, $contents)) {
            throw new \LogicException("failed to write {$this->dataFilePath}");
        }
    }
    public function getCreator(): string
    {
        return $this->getData()['creator'] ?? '';
    }

    public function getTitle(): string
    {
        return $this->getData()['title'] ?? '';
    }

    public function getManifestHeader(): string
    {
        return $this->getData()['manifestHeader'] ?? '';
    }

    public function getManifestFooter(): string
    {
        return $this->getData()['manifestFooter'] ?? '';
    }

    public function getAdditionalTasks(): array
    {
        return $this->getData()['additionalTasks'] ?? [];
    }

    public function getFotoCount():int
    {
         return $this->getData()['fotoCount'] ?? 12;
    }

    public function getPasswordHash(): string
    {
        return $this->getData()['passwordHash'] ?? self::hashPassword('rwnAdmin');
    }

    public static function hashPassword(string $password): string
    {
        return sha1(json_encode([strlen($password), $password, 'salt:fdsf#$@f$%hgfh%FHNMfg&jhg&%^&$']));
    }
}
