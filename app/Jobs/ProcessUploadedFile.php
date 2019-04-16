<?php

namespace App\Jobs;

use App\CPM\Objects\UploadedFile;
use App\CPM\Repositories\ICD9PhecodeRepository;
use App\CPM\Repositories\ICD9Repository;
use App\CPM\Repositories\ICD9RepositorySQL;
use App\CPM\Repositories\PhecodeRepository;
use App\CPM\Services\ICD9PhecodeUploadService;
use App\CPM\Services\ICD9UploadService;
use App\CPM\Services\PhecodeUploadService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessUploadedFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $file;

    /**
     * Create a new job instance.
     *
     * @param UploadedFile $file
     *
     * @return void
     */
    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     * @param ICD9UploadService $icd9Service
     * @param PhecodeUploadService $phecodeService
     * @param ICD9PhecodeUploadService $icd9PhecodeService
     * @return void
     */
    public function handle(
        ICD9UploadService $icd9Service,
        PhecodeUploadService $phecodeService,
        ICD9PhecodeUploadService $icd9PhecodeService
    ) {
        if (!empty($this->file->getPath())) {
            $fileContent = file_get_contents(storage_path('app/' . $this->file->getPath()));

            switch ($this->file->getType()) {
                case UploadedFile::ICD9:
                    $icd9Service->upload($fileContent, $this->file->getHeader());
                    break;
                case UploadedFile::PHECODE:
                    $phecodeService->upload($fileContent, $this->file->getHeader());
                    break;
                case UploadedFile::ICD9_PHECODE:
                    $icd9PhecodeService->upload($fileContent, $this->file->getHeader());
                    break;
            }
        }
    }
}
