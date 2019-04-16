<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/13/19
 * Time: 12:53 AM
 */

namespace Tests\Unit\Services;

use App\CPM\Repositories\ICD9Repository;
use App\CPM\Repositories\ICD9RepositorySQL;
use App\CPM\Services\ICD9UploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ICD9UploadServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ICD9UploadService
     */
    private $service;

    /**
     * @var ICD9Repository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(ICD9Repository::class, ICD9RepositorySQL::class);

        $this->repository = resolve(ICD9Repository::class);
        $this->service = resolve(ICD9UploadService::class);
    }

    public function testUploadingICD9()
    {
        $code0 = '001.12,3';
        $desc0 = $this->faker->sentence;

        $code1 = '003.2';
        $desc1 = $this->faker->sentence;

        $code2 = '034.1';
        $desc2 = $this->faker->sentence;

        $lines = "\"$code0\",\"$desc0\"\r\n\"$code1\",\"$desc1\"\n\"$code2\",\"$desc2\"";

        $count = $this->service->upload($lines);

        $actualICD9Array = [];

        foreach ($this->repository->getAll() as $icd9) {
            $actualICD9Array[] = $icd9;
        }

        $this->assertEquals($code0, $actualICD9Array[0]->getCode());
        $this->assertEquals($desc0, $actualICD9Array[0]->getDescription());

        $this->assertEquals($code1, $actualICD9Array[1]->getCode());
        $this->assertEquals($desc1, $actualICD9Array[1]->getDescription());

        $this->assertEquals($code2, $actualICD9Array[2]->getCode());
        $this->assertEquals($desc2, $actualICD9Array[2]->getDescription());

        $this->assertEquals(3, $count);
    }

    public function testUploadingICD9WithHeader()
    {
        $header0 = 'Header0';
        $header1 = 'Header1';

        $code0 = '003.2';
        $desc0 = $this->faker->sentence;

        $code1 = '034.1';
        $desc1 = $this->faker->sentence;

        $lines = "\"$header0\",\"$header1\"\r\n\"$code0\",\"$desc0\"\n\"$code1\",\"$desc1\"";

        $count = $this->service->upload($lines, $header = true);

        $actualICD9Array = [];

        foreach ($this->repository->getAll() as $icd9) {
            $actualICD9Array[] = $icd9;
        }

        $this->assertEquals($code0, $actualICD9Array[0]->getCode());
        $this->assertEquals($desc0, $actualICD9Array[0]->getDescription());

        $this->assertEquals($code1, $actualICD9Array[1]->getCode());
        $this->assertEquals($desc1, $actualICD9Array[1]->getDescription());

        $this->assertEquals(2, $count);
    }

    public function testGettingErrorLog()
    {
        $code0 = '001.12,3';
        $desc0 = $this->faker->sentence;

        $code1 = '003.2';
        $desc1 = $this->faker->sentence;

        $code2 = '034.1';
        $desc2 = $this->faker->sentence;

        $lines = "\"$code0\",\"$desc0\"\r\n\"$code1\",\"$desc1\",\"$desc1\"\n\"$code2\",\"$desc2\"";

        $count = $this->service->upload($lines);

        $actualICD9Array = [];

        foreach ($this->repository->getAll() as $icd9) {
            $actualICD9Array[] = $icd9;
        }

        $this->assertEquals($code0, $actualICD9Array[0]->getCode());
        $this->assertEquals($desc0, $actualICD9Array[0]->getDescription());

        $this->assertEquals($code2, $actualICD9Array[1]->getCode());
        $this->assertEquals($desc2, $actualICD9Array[1]->getDescription());

        $this->assertEquals(2, $count);

        $this->assertCount(1, $this->service->getErrorLog()->getErrors());
    }
}
