<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 9:12 AM
 */

namespace Tests\Unit\Repositories;

use App\CPM\Objects\ICD9;
use App\CPM\Repositories\ICD9Repository;
use App\CPM\Repositories\ICD9RepositorySQL;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ICD9RepositorySQLTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ICD9Repository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new ICD9RepositorySQL();
    }

    public function testAddingICD9()
    {
        $code = '120.23';
        $description = 'Rare ICD9 Disease';

        $icd9 = new ICD9(
            $code,
            $description
        );

        $this->repository->add($icd9);

        $actualICD9 = $this->repository->get($code)->first();

        $this->assertEquals($code, $actualICD9->getCode());
        $this->assertEquals($description, $actualICD9->getDescription());
    }

    public function testAddingICD9WithEmptyDescription()
    {
        $code = '120.23';
        $description = null;

        $icd9 = new ICD9(
            $code,
            $description ?? ''
        );

        $this->repository->add($icd9);

        $actualICD9 = $this->repository->get($code)->first();

        $this->assertEquals($code, $actualICD9->getCode());
        $this->assertEmpty($actualICD9->getDescription());
    }

    public function testAddingDuplicateICD9()
    {
        $code = '120.23';
        $description = 'Rare ICD9 Disease';

        $icd9 = new ICD9(
            $code,
            $description
        );

        $result = $this->repository->add($icd9);

        $this->assertTrue($result);

        $result = $this->repository->add($icd9);

        $this->assertFalse($result);

        $actualICD9 = $this->repository->get($code)->first();

        $this->assertEquals($code, $actualICD9->getCode());
        $this->assertEquals($description, $actualICD9->getDescription());
    }

    public function testGettingNotFoundICD9()
    {
        $code = '120.23';

        $actualICD9 = $this->repository->get($code)->first();

        $this->assertNull($actualICD9);
    }

    public function testGettingAllICD9()
    {
        $count = 8;
        $expectedRecords = $this->crossGenerateICD9Phecode($count);

        $allICD9s = $this->repository->getAll();

        foreach ($allICD9s as $index => $icd9) {
            $this->assertEquals(strtoupper($expectedRecords[$index]['icd9']), $icd9->getCode());
            $this->assertEquals($expectedRecords[$index]['icd9Description'], $icd9->getDescription());
        }
        $this->assertCount($count, $allICD9s);
    }

    public function testGettingICD9Typeahead()
    {
        $prefix = '__TEST__';

        $expectedCode = $prefix . strtoupper($this->faker->word);
        $expectedDescription = $this->faker->sentence;

        $this->repository->add(
            new ICD9(
                $expectedCode,
                $expectedDescription
            )
        );

        $this->repository->add(
            new ICD9(
                $this->faker->word,
                $this->faker->sentence
            )
        );

        $actualICD9 = $this->repository->get($prefix, true)->first();

        $this->assertEquals($expectedCode, $actualICD9->getCode());
        $this->assertEquals($expectedDescription, $actualICD9->getDescription());
    }

    public function testUpdatingICD9()
    {
        $code = '120.23';
        $description = 'Rare ICD9 disease';

        $icd9 = new ICD9(
            $code,
            $description
        );

        $this->repository->add($icd9);

        $actualICD9 = $this->repository->get($code)->first();

        $this->assertEquals($code, $actualICD9->getCode());
        $this->assertEquals($description, $actualICD9->getDescription());

        $newDescription = 'New strange disease';

        $newICD9 = new ICD9(
            $code,
            $newDescription
        );

        $countUpdate = $this->repository->update($newICD9);

        $updatedICD9 = $this->repository->get($code)->first();

        $this->assertEquals(1, $countUpdate);
        $this->assertEquals($code, $updatedICD9->getCode());
        $this->assertEquals($newDescription, $updatedICD9->getDescription());
    }

    public function testAddingWithAddingOrUpdatingICD9()
    {
        $code = '120.23';
        $description = 'Rare ICD9 Disease';

        $icd9 = new ICD9(
            $code,
            $description
        );

        $this->repository->addOrUpdate($icd9);

        $actualICD9 = $this->repository->get($code)->first();

        $this->assertEquals($code, $actualICD9->getCode());
        $this->assertEquals($description, $actualICD9->getDescription());
    }

    public function testUpdatingWithAddingOrUpdatingICD9()
    {
        $code = '120.23';
        $description = 'Rare ICD9 disease';

        $icd9 = new ICD9(
            $code,
            $description
        );

        $this->repository->addOrUpdate($icd9);

        $actualICD9 = $this->repository->get($code)->first();

        $this->assertEquals($code, $actualICD9->getCode());
        $this->assertEquals($description, $actualICD9->getDescription());

        $newDescription = 'New strange disease';

        $newICD9 = new ICD9(
            $code,
            $newDescription
        );

        $isUpdate = $this->repository->addOrUpdate($newICD9);

        $updatedICD9 = $this->repository->get($code)->first();

        $this->assertTrue($isUpdate);
        $this->assertEquals($code, $updatedICD9->getCode());
        $this->assertEquals($newDescription, $updatedICD9->getDescription());
    }

    public function testDeletingICD9()
    {
        $code = '120.23';
        $description = 'Rare ICD9 disease';

        $icd9 = new ICD9(
            $code,
            $description
        );

        $this->repository->add($icd9);

        $actualICD9 = $this->repository->get($code)->first();

        $this->assertNotNull($actualICD9);

        $countDelete = $this->repository->delete($code);

        $actualICD9 = $this->repository->get($code)->first();

        $this->assertEquals(1, $countDelete);
        $this->assertNull($actualICD9);
    }
}
