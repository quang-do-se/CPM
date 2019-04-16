<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/13/19
 * Time: 10:30 AM
 */

namespace App\CPM\Objects;

class ErrorLog
{
    /**
     * @var array
     */
    private $errorLog = [];

    public function addMessage(string $message)
    {
        $this->errorLog[] = $message;
    }

    public function hasError(): bool
    {
        return !empty($this->errorLog);
    }

    public function getErrors(): array
    {
        return $this->errorLog;
    }

    public function empty(): void
    {
        $this->errorLog = [];
    }

    public function addLog(ErrorLog $log)
    {
        $this->errorLog = array_merge($this->errorLog, $log->errorLog);
    }
}
