<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/11/19
 * Time: 3:20 PM
 */

namespace Tests\Unit\Objects;

use App\CPM\Objects\ICD9Phecode;
use Tests\TestCase;

class ICD9PhecodeTest extends TestCase
{
    public function testCreateObject()
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

        $this->assertEquals($phecode, $icd9Phecode->getPhecode());
        $this->assertEquals($phecodeDescription, $icd9Phecode->getPhecodeDescription());
        $this->assertEquals($icd9, $icd9Phecode->getICD9());
        $this->assertEquals($icd9Description, $icd9Phecode->getICD9Description());
    }

    public function testToJson()
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

        $expectedJson = json_encode([
            'icd9' => $icd9,
            'phecode' => $phecode,
            'icd9Description' => $icd9Description,
            'phecodeDescription' => $phecodeDescription,
        ]);

        $this->assertEquals($expectedJson, $icd9Phecode->toJson());
    }

    public function testToArray()
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

        $expectedArray = [
            'icd9' => $icd9,
            'phecode' => $phecode,
            'icd9Description' => $icd9Description,
            'phecodeDescription' => $phecodeDescription,
        ];

        $this->assertEquals($expectedArray, $icd9Phecode->toArray());
    }
}
