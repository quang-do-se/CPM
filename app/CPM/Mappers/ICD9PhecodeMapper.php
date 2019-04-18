<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 6:11 PM
 */

namespace App\CPM\Mappers;

use App\CPM\Objects\ICD9Phecode;

class ICD9PhecodeMapper
{
    public function mapStringToObject(string $input): ICD9Phecode
    {
        $parsedArray = str_getcsv($input);

        if (count($parsedArray) !== 2) {
            throw new \InvalidArgumentException(
                "Input should have a ICD-9 code and a Phecode, exactly. Input: ''"
            );
        }

        list($icd9, $phecode) = $parsedArray;

        return new ICD9Phecode(
            $icd9,
            $phecode
        );
    }
}
