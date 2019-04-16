<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 6:05 PM
 */

namespace Tests\Unit\Mappers;

use App\CPM\Mappers\PhecodeMapper;
use Tests\TestCase;

class PhecodeMapperTest extends TestCase
{
    /**
     * @var PhecodeMapper
     */
    private $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mapper = new PhecodeMapper();
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
