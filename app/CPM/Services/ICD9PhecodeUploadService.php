<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/13/19
 * Time: 12:50 AM
 */

namespace App\CPM\Services;

use App\CPM\Mappers\ICD9PhecodeMapper;
use App\CPM\Objects\ErrorLog;
use App\CPM\Repositories\ICD9PhecodeRepository;

class ICD9PhecodeUploadService
{
    private $mapper;
    private $repository;
    private $errorLog;

    public function __construct(ICD9PhecodeMapper $mapper, ICD9PhecodeRepository $repository, ErrorLog $errorLog)
    {
        $this->mapper = $mapper;
        $this->repository = $repository;
        $this->errorLog = $errorLog;
    }

    public function upload(?string $lines, bool $header = false): int
    {
        $linesArray = preg_split("/\r\n|\r|\n/", $lines);

        $countUpload = 0;

        if ($header) {
            unset($linesArray[0]);
        }

        foreach ($linesArray as $index => $line) {
            try {
                $icd9Phecode = $this->mapper->mapStringToObject($line);

                $result = $this->repository->add($icd9Phecode);

                if ($result) {
                    $countUpload++;
                }
            } catch (\InvalidArgumentException $e) {
                $this->errorLog->addMessage($e->getMessage());
            }
        }

        return $countUpload;
    }

    public function getErrorLog(): ErrorLog
    {
        return $this->errorLog;
    }
}
