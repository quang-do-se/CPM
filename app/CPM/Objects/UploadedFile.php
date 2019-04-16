<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/14/19
 * Time: 11:29 PM
 */

namespace App\CPM\Objects;

class UploadedFile
{
    private $path;
    private $type;
    private $header;

    const ICD9 = 'icd9';
    const ICD9_PHECODE = 'icd9_phecode';
    const PHECODE = 'phecode';

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setHeader(bool $header): void
    {
        $this->header = $header;
    }

    public function getHeader(): bool
    {
        return $this->header;
    }
}
