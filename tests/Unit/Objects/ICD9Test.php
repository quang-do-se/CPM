<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 4:29 PM
 */

namespace Tests\Unit\Objects;

use App\CPM\Objects\ICD9;
use Tests\TestCase;

class ICD9Test extends TestCase
{
    public function testCreatingICD9()
    {
        $code = 'a123.23';
        $description = 'description of icd9';

        $icd9 = new ICD9($code, $description);

        $this->assertEquals(strtoupper($code), $icd9->getCode());
        $this->assertEquals($description, $icd9->getDescription());
    }

    public function testCreatingICD9WithEmptyDescription()
    {
        $code = '123.23';

        $icd9 = new ICD9($code);

        $this->assertEquals($code, $icd9->getCode());
        $this->assertEmpty($icd9->getDescription());
    }

    public function testToArray()
    {
        $code = 'I123';
        $description = 'ICD9 Description';

        $icd9 = new ICD9(
            $code,
            $description
        );

        $expectedArray = [
            'code' => $code,
            'description' => $description,
        ];

        $this->assertEquals($expectedArray, $icd9->toArray());
    }
}
