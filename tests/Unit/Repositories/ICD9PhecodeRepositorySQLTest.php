<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/11/19
 * Time: 1:49 PM
 */

namespace Tests\Unit\Repositories;

use App\CPM\Objects\ICD9Phecode;
use App\CPM\Repositories\ICD9PhecodeRepository;
use App\CPM\Repositories\ICD9PhecodeRepositorySQL;
use App\CPM\Repositories\ICD9Repository;
use App\CPM\Repositories\ICD9RepositorySQL;
use App\CPM\Repositories\PhecodeRepository;
use App\CPM\Repositories\PhecodeRepositorySQL;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ICD9PhecodeRepositorySQLTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ICD9PhecodeRepository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(ICD9Repository::class, ICD9RepositorySQL::class);
        $this->app->singleton(PhecodeRepository::class, PhecodeRepositorySQL::class);
        $this->repository = resolve(ICD9PhecodeRepositorySQL::class);
    }

    public function testGettingICD9PhecodeByPheCode()
    {
        $expectedList = $this->crossGenerateICD9Phecode(7, 8);

        $searchedPhecode = $expectedList[0]['phecode'];

        $searchedRecords = $this->repository->getByPhecode($searchedPhecode);

        foreach ($searchedRecords as $record) {
            $this->assertEquals($searchedPhecode, $record->getPhecode());
        }

        $this->assertCount(7, $searchedRecords);
    }

    public function testGettingICD9PhecodeByPheCodeWithLimitOffset()
    {
        $expectedList = $this->crossGenerateICD9Phecode(7, 8);

        $searchedPhecode = $expectedList[0]['phecode'];

        $searchedRecords = $this->repository->getByPhecode($searchedPhecode, false, 0, 3);

        foreach ($searchedRecords as $index => $record) {
            $this->assertEquals($searchedPhecode, $record->getPhecode());
        }

        $this->assertCount(3, $searchedRecords);
    }

    public function testGettingICD9PhecodeByPheCodeTypeahead()
    {
        $prefix = '__TEST__';
        $this->crossGenerateICD9Phecode(6, 1);
        $this->crossGenerateICD9Phecode(13, 1, $prefix);

        $searchedRecords = $this->repository->getByPhecode($prefix, true);

        $allRecords = $this->repository->getAll();

        $this->assertCount(13, $searchedRecords);
        $this->assertCount(19, $allRecords);
    }

    public function testGettingICD9PhecodeByICD9()
    {
        $numberOfICD9 = 7;
        $numberOfPhecode = 9;

        $expectedList = $this->crossGenerateICD9Phecode($numberOfICD9, $numberOfPhecode);

        $searchedICD9 = $expectedList[0]['icd9'];

        $searchedRecords = $this->repository->getByICD9($searchedICD9);

        foreach ($searchedRecords as $record) {
            $this->assertEquals($searchedICD9, $record->getICD9());
        }

        $this->assertCount($numberOfPhecode, $searchedRecords);
    }

    public function testGettingICD9PhecodeByICD9WithLimitOffset()
    {
        $numberOfICD9 = 7;
        $numberOfPhecode = 9;

        $expectedList = $this->crossGenerateICD9Phecode($numberOfICD9, $numberOfPhecode);

        $expectedICD9 = $expectedList[0]['icd9'];

        $searchedRecords = $this->repository->getByICD9($expectedICD9, false, 0, 3);

        foreach ($searchedRecords as $record) {
            $this->assertEquals($expectedICD9, $record->getICD9());
        }

        $this->assertCount(3, $searchedRecords);
    }

    public function testGettingICD9PhecodeByICD9Typeahead()
    {
        $prefix = '__TEST__';
        $this->crossGenerateICD9Phecode(6, 1);
        $this->crossGenerateICD9Phecode(13, 1, $prefix);

        $searchedRecords = $this->repository->getByICD9($prefix, true);

        $allRecords = $this->repository->getAll();

        $this->assertCount(13, $searchedRecords);
        $this->assertCount(19, $allRecords);
    }

    public function testAddingICD9Phecode()
    {
        $phecode = 'A320';
        $phecodeDescription = 'Phecode Disease';
        $icd9 = 'I123';
        $icd9Description = 'ICD9 Description';

        $icd9Phecode = new ICD9Phecode(
            $icd9,
            $phecode,
            $icd9Description,
            $phecodeDescription
        );

        $this->repository->add($icd9Phecode);

        $searchedRecords = $this->repository->getByICD9($icd9);

        $this->assertCount(1, $searchedRecords);
    }

    public function testAddingDuplicateICD9Phecode()
    {
        $phecode = 'A320';
        $icd9 = 'I123';

        $icd9Phecode = new ICD9Phecode(
            $icd9,
            $phecode
        );

        $result = $this->repository->add($icd9Phecode);
        $this->assertTrue($result);

        $result = $this->repository->add($icd9Phecode);
        $this->assertFalse($result);

        $searchedRecords = $this->repository->getByICD9($icd9);

        $this->assertCount(1, $searchedRecords);
    }

    public function testDeletingICD9Phecode()
    {
        $phecode = 'A320';
        $icd9 = 'I123';

        $icd9Phecode = new ICD9Phecode(
            $icd9,
            $phecode
        );

        $isAdded = $this->repository->add($icd9Phecode);
        $this->assertTrue($isAdded);

        $searchedRecords = $this->repository->getAll();

        $this->assertCount(1, $searchedRecords);
        $this->assertEquals($icd9, $searchedRecords->get(0)->getICD9());
        $this->assertEquals($phecode, $searchedRecords->get(0)->getPhecode());

        $deleteCount = $this->repository->delete($icd9Phecode);
        $this->assertEquals(1, $deleteCount);

        $searchedRecords = $this->repository->getAll();
        $this->assertCount(0, $searchedRecords);
    }

    public function testGettingICD9PhecodeByICD9WithoutPhecode()
    {
        $expectedList = $this->generateICD9();

        $expectedRecord = $expectedList[0];

        $searchedRecord = $this->repository->getByICD9($expectedRecord['icd9'])->get(0);

        $this->assertEquals($expectedRecord['icd9'], $searchedRecord->getICD9());
        $this->assertEquals($expectedRecord['icd9Description'], $searchedRecord->getICD9Description());
        $this->assertEmpty($searchedRecord->getPhecode());
        $this->assertEmpty($searchedRecord->getPhecodeDescription());
    }


    public function testGettingICD9PhecodeByPhecodeWithoutICD9()
    {
        $expectedList = $this->generatePhecode();

        $expectedRecord = $expectedList[0];

        $searchedRecord = $this->repository->getByPhecode($expectedRecord['phecode'])->get(0);

        $this->assertEquals($expectedRecord['phecode'], $searchedRecord->getPhecode());
        $this->assertEquals($expectedRecord['phecodeDescription'], $searchedRecord->getPhecodeDescription());
        $this->assertEmpty($searchedRecord->getICD9());
        $this->assertEmpty($searchedRecord->getICD9Description());
    }
}
