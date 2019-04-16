<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 6:05 PM
 */

namespace Tests\Unit\Mappers;

use App\CPM\Mappers\ICD9PhecodeMapper;
use Tests\TestCase;

class ICD9PhecodeMapperTest extends TestCase
{
    /**
     * @var ICD9PhecodeMapper
     */
    private $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mapper = new ICD9PhecodeMapper();
    }

    public function testMappingStringToObject()
    {
        $icd9 = '123.43,23';
        $phecode = '987.2,12';

        $input = "\"$icd9\",\"$phecode\"";

        $object = $this->mapper->mapStringToObject($input);

        $this->assertEquals($icd9, $object->getICD9());
        $this->assertEquals($phecode, $object->getPhecode());
    }

    public function testMappingStringToObjectInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $input = '"123.2","987.2","HelloWorld"';

        $this->mapper->mapStringToObject($input);
    }
}
