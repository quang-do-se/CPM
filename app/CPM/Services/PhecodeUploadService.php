<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/13/19
 * Time: 12:49 AM
 */

namespace App\CPM\Services;

use App\CPM\Mappers\PhecodeMapper;
use App\CPM\Objects\ErrorLog;
use App\CPM\Repositories\PhecodeRepository;
use Illuminate\Support\Facades\Log;

class PhecodeUploadService
{
    private $mapper;
    private $repository;
    private $errorLog;

    public function __construct(PhecodeMapper $mapper, PhecodeRepository $repository, ErrorLog $errorLog)
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
                $phecode = $this->mapper->mapStringToObject($line);

                $result = $this->repository->addOrUpdate($phecode);

                if ($result) {
                    $countUpload++;
                } else {
                    $this->reportError("Line $index: Failed to add line '$line'");
                }
            } catch (\InvalidArgumentException $e) {
                $this->reportError("Line $index: " . $e->getMessage());
            }
        }

        return $countUpload;
    }

    private function reportError(string $message): void
    {
        $this->errorLog->addMessage($message);
        Log::error($message);
    }

    public function getErrorLog(): ErrorLog
    {
        return $this->errorLog;
    }
}
