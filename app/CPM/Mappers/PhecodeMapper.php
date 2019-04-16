<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 6:10 PM
 */

namespace App\CPM\Mappers;

use App\CPM\Objects\Phecode;

class PhecodeMapper
{
    public function mapStringToObject(string $input): Phecode
    {
        $parsedArray = str_getcsv($input);

        if (count($parsedArray) !== 2) {
            throw new \InvalidArgumentException(
                'Input should have a code and a description, exactly.'
            );
        }

        list($code, $description) = $parsedArray;

        return new Phecode(
            $code,
            $description
        );
    }
}
