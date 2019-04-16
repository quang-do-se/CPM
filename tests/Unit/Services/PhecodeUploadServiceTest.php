<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/13/19
 * Time: 12:53 AM
 */

namespace Tests\Unit\Services;

use App\CPM\Repositories\PhecodeRepository;
use App\CPM\Repositories\PhecodeRepositorySQL;
use App\CPM\Services\PhecodeUploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhecodeUploadServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @var PhecodeUploadService
     */
    private $service;

    /**
     * @var PhecodeRepository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(PhecodeRepository::class, PhecodeRepositorySQL::class);

        $this->repository = resolve(PhecodeRepository::class);
        $this->service = resolve(PhecodeUploadService::class);
    }

    public function testUploadingPhecode()
    {
        $code0 = '001.12,2';
        $desc0 = $this->faker->sentence;

        $code1 = '003.2';
        $desc1 = $this->faker->sentence;

        $code2 = '034.1';
        $desc2 = $this->faker->sentence;

        $lines = "\"$code0\",\"$desc0\"\r\n\"$code1\",\"$desc1\"\n\"$code2\",\"$desc2\"";

        $count = $this->service->upload($lines);

        $actualPhecodeArray = [];

        foreach ($this->repository->getAll() as $phecode) {
            $actualPhecodeArray[] = $phecode;
        }

        $this->assertEquals($code0, $actualPhecodeArray[0]->getCode());
        $this->assertEquals($desc0, $actualPhecodeArray[0]->getDescription());

        $this->assertEquals($code1, $actualPhecodeArray[1]->getCode());
        $this->assertEquals($desc1, $actualPhecodeArray[1]->getDescription());

        $this->assertEquals($code2, $actualPhecodeArray[2]->getCode());
        $this->assertEquals($desc2, $actualPhecodeArray[2]->getDescription());

        $this->assertEquals(3, $count);
    }

    public function testUploadingPhecodeWithHeader()
    {
        $header0 = 'Header0';
        $header1 = 'Header1';

        $code0 = '003.2';
        $desc0 = $this->faker->sentence;

        $code1 = '034.1';
        $desc1 = $this->faker->sentence;

        $lines = "\"$header0\",\"$header1\"\r\n\"$code0\",\"$desc0\"\n\"$code1\",\"$desc1\"";

        $count = $this->service->upload($lines, $header = true);

        $actualPhecodeArray = [];

        foreach ($this->repository->getAll() as $phecode) {
            $actualPhecodeArray[] = $phecode;
        }

        $this->assertEquals($code0, $actualPhecodeArray[0]->getCode());
        $this->assertEquals($desc0, $actualPhecodeArray[0]->getDescription());

        $this->assertEquals($code1, $actualPhecodeArray[1]->getCode());
        $this->assertEquals($desc1, $actualPhecodeArray[1]->getDescription());

        $this->assertEquals(2, $count);
    }

    public function testGettingErrorLog()
    {
        $code0 = '001.12,2';
        $desc0 = $this->faker->sentence;

        $code1 = '003.2';
        $desc1 = $this->faker->sentence;

        $code2 = '034.1';
        $desc2 = $this->faker->sentence;

        $lines = "\"$code0\",\"$desc0\"\r\n\"$code1\",\"$desc1\",\"$desc1\"\n\"$code2\",\"$desc2\"";

        $count = $this->service->upload($lines);

        $actualPhecodeArray = [];

        foreach ($this->repository->getAll() as $phecode) {
            $actualPhecodeArray[] = $phecode;
        }

        $this->assertEquals($code0, $actualPhecodeArray[0]->getCode());
        $this->assertEquals($desc0, $actualPhecodeArray[0]->getDescription());

        $this->assertEquals($code2, $actualPhecodeArray[1]->getCode());
        $this->assertEquals($desc2, $actualPhecodeArray[1]->getDescription());

        $this->assertEquals(2, $count);

        $this->assertCount(1, $this->service->getErrorLog()->getErrors());
    }
}
