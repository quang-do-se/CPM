<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 1:33 PM
 */

namespace Tests\Unit\Repositories;

use App\CPM\Objects\Phecode;
use App\CPM\Repositories\PhecodeRepository;
use App\CPM\Repositories\PhecodeRepositorySQL;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhecodeRepositorySQLTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var PhecodeRepository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new PhecodeRepositorySQL();
    }

    public function testAddingPhecode()
    {
        $code = '120.23';
        $description = 'Rare Phecode Disease';

        $phecode = new Phecode(
            $code,
            $description
        );

        $this->repository->add($phecode);

        $actualPhecode = $this->repository->get($code)->first();

        $this->assertEquals($code, $actualPhecode->getCode());
        $this->assertEquals($description, $actualPhecode->getDescription());
    }

    public function testAddingDuplicatePhecode()
    {
        $code = '120.23';
        $description = 'Rare Phecode Disease';

        $phecode = new Phecode(
            $code,
            $description
        );

        $result = $this->repository->add($phecode);

        $this->assertTrue($result);

        $result = $this->repository->add($phecode);

        $this->assertFalse($result);

        $actualPhecode = $this->repository->get($code)->first();

        $this->assertEquals($code, $actualPhecode->getCode());
        $this->assertEquals($description, $actualPhecode->getDescription());
    }

    public function testGettingNotFoundPhecode()
    {
        $code = '120.23';

        $actualPhecode = $this->repository->get($code)->first();

        $this->assertNull($actualPhecode);
    }

    public function testGettingAllPhecodes()
    {
        $count = 8;
        $expectedRecords = $this->crossGenerateICD9Phecode(1, $count);

        $allPhecodes = $this->repository->getAll();

        foreach ($allPhecodes as $index => $phecode) {
            $this->assertEquals(strtoupper($expectedRecords[$index]['phecode']), $phecode->getCode());
            $this->assertEquals($expectedRecords[$index]['phecodeDescription'], $phecode->getDescription());
        }
        $this->assertCount($count, $allPhecodes);
    }

    public function testGettingPhecodeTypeahead()
    {
        $prefix = '__TEST__';

        $expectedCode = $prefix . strtoupper($this->faker->word);
        $expectedDescription = $this->faker->sentence;

        $this->repository->add(
            new Phecode(
                $expectedCode,
                $expectedDescription
            )
        );

        $this->repository->add(
            new Phecode(
                $this->faker->word,
                $this->faker->sentence
            )
        );

        $actualPhecode = $this->repository->get($prefix, true)->first();

        $this->assertEquals($expectedCode, $actualPhecode->getCode());
        $this->assertEquals($expectedDescription, $actualPhecode->getDescription());
    }

    public function testUpdatingPhecode()
    {
        $code = '120.23';
        $description = 'Rare Phecode disease';

        $phecode = new Phecode(
            $code,
            $description
        );

        $this->repository->add($phecode);

        $actualPhecode = $this->repository->get($code)->first();

        $this->assertEquals($code, $actualPhecode->getCode());
        $this->assertEquals($description, $actualPhecode->getDescription());

        $newDescription = 'New strange disease';

        $newICD9 = new Phecode(
            $code,
            $newDescription
        );

        $countUpdate = $this->repository->update($newICD9);

        $updatedPhecode = $this->repository->get($code)->first();

        $this->assertEquals(1, $countUpdate);
        $this->assertEquals($code, $updatedPhecode->getCode());
        $this->assertEquals($newDescription, $updatedPhecode->getDescription());
    }

    public function testAddingWithAddingOrUpdatingPhecode()
    {
        $code = '120.23';
        $description = 'Rare Phecode Disease';

        $phecode = new Phecode(
            $code,
            $description
        );

        $this->repository->addOrUpdate($phecode);

        $actualPhecode = $this->repository->get($code)->first();

        $this->assertEquals($code, $actualPhecode->getCode());
        $this->assertEquals($description, $actualPhecode->getDescription());
    }

    public function testUpdatingWithAddingOrUpdatingPhecode()
    {
        $code = '120.23';
        $description = 'Rare Phecode disease';

        $phecode = new Phecode(
            $code,
            $description
        );

        $this->repository->addOrUpdate($phecode);

        $actualPhecode = $this->repository->get($code)->first();

        $this->assertEquals($code, $actualPhecode->getCode());
        $this->assertEquals($description, $actualPhecode->getDescription());

        $newDescription = 'New strange disease';

        $newICD9 = new Phecode(
            $code,
            $newDescription
        );

        $countUpdate = $this->repository->addOrUpdate($newICD9);

        $updatedPhecode = $this->repository->get($code)->first();

        $this->assertEquals(1, $countUpdate);
        $this->assertEquals($code, $updatedPhecode->getCode());
        $this->assertEquals($newDescription, $updatedPhecode->getDescription());
    }

    public function testDeletingPhecode()
    {
        $code = '120.23';
        $description = 'Rare Phecode disease';

        $phecode = new Phecode(
            $code,
            $description
        );

        $this->repository->add($phecode);

        $actualPhecode = $this->repository->get($code)->first();

        $this->assertNotNull($actualPhecode);

        $countDelete = $this->repository->delete($code);

        $actualPhecode = $this->repository->get($code)->first();

        $this->assertEquals(1, $countDelete);
        $this->assertNull($actualPhecode);
    }
}
