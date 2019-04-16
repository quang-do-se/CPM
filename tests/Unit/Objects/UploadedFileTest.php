<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/14/19
 * Time: 6:48 PM
 */

namespace Tests\Unit\Objects;

use App\CPM\Objects\UploadedFile;
use Carbon\Carbon;
use Tests\TestCase;

class UploadedFileTest extends TestCase
{
    public function testCreatingUploadedFile()
    {
        $path = storage_path('file_' . Carbon::now()->format('Y-m-d His'));

        $uploadedFile = new UploadedFile();

        $uploadedFile->setPath($path);
        $uploadedFile->setType(UploadedFile::ICD9);
        $uploadedFile->setHeader(true);

        $this->assertEquals($path, $uploadedFile->getPath());
        $this->assertEquals(UploadedFile::ICD9, $uploadedFile->getType());
        $this->assertTrue($uploadedFile->getHeader());
    }
}
