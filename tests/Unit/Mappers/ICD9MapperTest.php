<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 5:17 PM
 */

namespace Tests\Unit\Mappers;

use App\CPM\Mappers\ICD9Mapper;
use Tests\TestCase;

class ICD9MapperTest extends TestCase
{
    /**
     * @var ICD9Mapper
     */
    private $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mapper = new ICD9Mapper();
    }

    public function testMappingStringToObject()
    {
        $code = '123.43,23';
        $description = 'Some unknown disease, need to research';
        $input = "\"$code\",\"$description\"";

        $object = $this->mapper->mapStringToObject($input);

        $this->assertEquals($code, $object->getCode());
        $this->assertEquals($description, $object->getDescription());
    }

    public function testMappingStringToObjectInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $input = '"123.2","hello","world';

        $this->mapper->mapStringToObject($input);
    }
}
