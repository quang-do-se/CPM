<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/13/19
 * Time: 12:53 AM
 */

namespace Tests\Unit\Services;

use App\CPM\Repositories\ICD9PhecodeRepository;
use App\CPM\Repositories\ICD9PhecodeRepositorySQL;
use App\CPM\Services\ICD9PhecodeUploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ICD9PhecodeUploadServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ICD9PhecodeUploadService
     */
    private $service;

    /**
     * @var ICD9PhecodeRepository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(ICD9PhecodeRepository::class, ICD9PhecodeRepositorySQL::class);

        $this->repository = resolve(ICD9PhecodeRepository::class);
        $this->service = resolve(ICD9PhecodeUploadService::class);
    }

    public function testUploadingICD9Phecode()
    {
        $icd0 = '001.12,3';
        $phecode0 = '008';

        $icd1 = '003.2';
        $phecode1 = '008.5';

        $icd2 = '034.1';
        $phecode2 = '041.2';

        $lines = "\"$icd0\",\"$phecode0\"\r\n\"$icd1\",\"$phecode1\"\n\"$icd2\",\"$phecode2\"";

        $count = $this->service->upload($lines);

        $actualICD9PhecodeArray = [];

        foreach ($this->repository->getAll() as $icd9Phecode) {
            $actualICD9PhecodeArray[] = $icd9Phecode;
        }

        $this->assertEquals($icd0, $actualICD9PhecodeArray[0]->getICD9());
        $this->assertEquals($phecode0, $actualICD9PhecodeArray[0]->getPhecode());

        $this->assertEquals($icd1, $actualICD9PhecodeArray[1]->getICD9());
        $this->assertEquals($phecode1, $actualICD9PhecodeArray[1]->getPhecode());

        $this->assertEquals($icd2, $actualICD9PhecodeArray[2]->getICD9());
        $this->assertEquals($phecode2, $actualICD9PhecodeArray[2]->getPhecode());

        $this->assertEquals(3, $count);
    }

    public function testUploadingICD9PhecodeWithHeader()
    {
        $header0 = 'HEADER0';
        $header1 = 'HEADER1';

        $icd0 = '003.2';
        $phecode0 = '008.5';

        $icd1 = '034.1';
        $phecode1 = '041.2';

        $lines = "\"$header0\",\"$header1\"\r\n\"$icd0\",\"$phecode0\"\n\"$icd1\",\"$phecode1\"";

        $count = $this->service->upload($lines, $header = true);

        $actualICD9PhecodeArray = [];

        foreach ($this->repository->getAll() as $icd9Phecode) {
            $actualICD9PhecodeArray[] = $icd9Phecode;
        }

        $this->assertEquals($icd0, $actualICD9PhecodeArray[0]->getICD9());
        $this->assertEquals($phecode0, $actualICD9PhecodeArray[0]->getPhecode());

        $this->assertEquals($icd1, $actualICD9PhecodeArray[1]->getICD9());
        $this->assertEquals($phecode1, $actualICD9PhecodeArray[1]->getPhecode());

        $this->assertEquals(2, $count);
    }

    public function testGettingErrorLog()
    {
        $icd0 = '001.12,3';
        $phecode0 = '008';

        $icd1 = '003.2';
        $phecode1 = '008.5';

        $icd2 = '034.1';
        $phecode2 = '041.2';

        $lines = "\"$icd0\",\"$phecode0\"\r\n\"$icd1\",\"$phecode1\",\"$phecode1\"\n\"$icd2\",\"$phecode2\"";

        $count = $this->service->upload($lines);

        $actualICD9PhecodeArray = [];

        foreach ($this->repository->getAll() as $icd9Phecode) {
            $actualICD9PhecodeArray[] = $icd9Phecode;
        }

        $this->assertEquals($icd0, $actualICD9PhecodeArray[0]->getICD9());
        $this->assertEquals($phecode0, $actualICD9PhecodeArray[0]->getPhecode());

        $this->assertEquals($icd2, $actualICD9PhecodeArray[1]->getICD9());
        $this->assertEquals($phecode2, $actualICD9PhecodeArray[1]->getPhecode());

        $this->assertEquals(2, $count);

        $this->assertCount(1, $this->service->getErrorLog()->getErrors());
    }
}
