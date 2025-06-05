<?php

declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;
use Psr\Http\Message\UploadedFileInterface;

class User implements JsonSerializable
{
    protected string $id;
    protected string $dataFilePath;
    protected ?array $data = null;

    public function __construct(string $id, protected string $dataPath)
    {
        $this->id = $id;
        $this->dataFilePath = $this->dataPath . DIRECTORY_SEPARATOR . 'data.json';
    }

    public function deleteDataDir(): void
    {
        rename($this->dataPath, $this->dataPath . '.deleted');
    }


    public function getData(): array
    {
        if ($this->data) {
            return $this->data;
        }
        if (is_file($this->dataFilePath)) {
            $data = file_get_contents($this->dataPath . DIRECTORY_SEPARATOR . 'data.json');
            $this->data = json_decode($data, true);
        } else {
            $this->data = [];
        }
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
        $contents = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        $this->prepareDataDir();
        file_put_contents($this->dataFilePath, $contents);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->getData()['name'] ?? '';
    }

    public function getInfo(): string
    {
        return $this->getData()['info'] ?? '';
    }

    public function getSection(): string
    {
        return $this->getData()['section'] ?? '';
    }

    public function getSectionName(): string
    {
        $section=preg_replace('#\D*(\d+).*?$#','$1',$this->getSection());
        return in_array($section,['','1']) ? '' : "Etap $section";
    }

    public function getStatus(): string
    {
        return $this->getData()['status'] ?? 'opis w przygotowaniu';
    }

    public function getAuthor(): string
    {
        return $this->getData()['author'] ?? '';
    }

    public function getScore(): array
    {
        return $this->getData()['score'] ?? [];
    }

    public function getPoints(): string
    {
        return (string)($this->getData()['totalScore'] ?? '');
    }

    public function getManifest(): string
    {
        return $this->getData()['manifest'] ?? '';
    }

    public function getImages(): array
    {
        $commonData = new CommonData;
        $images = [];
        for ($i = 1; $i <= $commonData->getFotoCount(); ++$i) {
            $images['image_' . $i] = ['id' => $i, 'src' => 'zagadka.png', 'solution' => '', 'exif' => []];
        }

        foreach ($this->getData()['images'] ?? [] as $img) {
            $images['image_' . $img['id']] = $img;
        }

        return $images;
    }

    public function addImage(UploadedFileInterface $file): string
    {
        $this->prepareDataDir();
        $temp = $this->dataPath . DIRECTORY_SEPARATOR . 'image_tmp';
        $file->moveTo($temp);
        $sha1 = sha1_file($temp);
        $ext = preg_replace('/^.*\./', '', $file->getClientFilename());
        $imageFile = $this->dataPath . DIRECTORY_SEPARATOR . 'image_' . substr($sha1, 0, 16) . '.' . $ext;
        rename($temp, $imageFile);
        return $imageFile;
    }

    private function prepareDataDir(): void
    {
        if (!is_dir($this->dataPath)) {
            mkdir($this->dataPath);
        }
    }

    function getManifestChunks(): array
    {
        $manifest = $this->getManifest().' ';
        $len = strlen($manifest);

        $mode = ']';
        $output = [];
        $current = '';
        $escape = false;
        $num = 1;
        for ($i = 0; $i < $len; ++$i) {
            $char = $manifest[$i];
            if ($escape) {
                $escape = false;
                continue;
            }

            if ($char == '\\') {
                $escape = true;
                continue;
            }


            if ($char == '[' || $char == ']' || $i == $len - 1) {
                if ($mode !== $char) {
                    $chunk = [
                        'type' => $mode == ']' ? 'text' : 'solution',
                        'text' => $current
                    ];
                    if ($chunk['type'] == 'solution') {
                        $chunk['size'] = max(5, mb_strlen($current), trim($current)==''?40:0);
                        $chunk['id'] = $num++;
                    }
                    $output[] = $chunk;
                    $current = '';
                    $mode = $char;
                }
                continue;
            }
            $current .= $char;
        }

        return $output;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'dataPath' => $this->dataPath,
        ];
    }


    public function getUserSolution():array{
        $dataFilePath = $this->dataPath . DIRECTORY_SEPARATOR . 'solution.json';
        if(!is_file($dataFilePath)){
            return [];
        }
        $data=file_get_contents($dataFilePath);
        return json_decode($data, true, JSON_THROW_ON_ERROR);
    }

    public function saveUserSolution(array $data):void{
        $dataFilePath = $this->dataPath . DIRECTORY_SEPARATOR . 'solution.json';
        $contents = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        file_put_contents($dataFilePath, $contents);
    }
}
