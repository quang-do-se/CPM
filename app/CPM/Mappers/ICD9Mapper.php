<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 5:54 PM
 */

namespace App\CPM\Mappers;

use App\CPM\Objects\ICD9;

class ICD9Mapper
{
    public function mapStringToObject(string $input): ICD9
    {
        $parsedArray = str_getcsv($input);

        if (count($parsedArray) !== 2) {
            throw new \InvalidArgumentException(
                "Input should have a code and a description, exactly. Input: '$input'"
            );
        }

        list($code, $description) = $parsedArray;

        return new ICD9(
            $code,
            $description
        );
    }
}
