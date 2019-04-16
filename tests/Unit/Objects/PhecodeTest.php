<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 4:29 PM
 */

namespace Tests\Unit\Objects;

use App\CPM\Objects\Phecode;
use Tests\TestCase;

class PhecodeTest extends TestCase
{
    public function testCreatingPhecode()
    {
        $code = '9800.1b';
        $description = 'description of phecode';

        $phecode = new Phecode($code, $description);

        $this->assertEquals(strtoupper($code), $phecode->getCode());
        $this->assertEquals($description, $phecode->getDescription());
    }

    public function testCreatingPhecodeWithEmptyDescription()
    {
        $code = '9800.1';

        $phecode = new Phecode($code);

        $this->assertEquals($code, $phecode->getCode());
        $this->assertEmpty($phecode->getDescription());
    }

    public function testToArray()
    {
        $code = 'P123';
        $description = 'Phecode Description';

        $phecode = new Phecode(
            $code,
            $description
        );

        $expectedArray = [
            'code' => $code,
            'description' => $description,
        ];

        $this->assertEquals($expectedArray, $phecode->toArray());
    }
}
