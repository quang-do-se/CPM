<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Faker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $faker;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = Faker\Factory::create();
    }

    public function crossGenerateICD9Phecode(
        int $numberOfICD9 = 1,
        int $numberOfPhecode = 1,
        string $prefix = '',
        string $suffix = ''
    ) {
        // Simulate many to many relationship
        // $numberOfICD9 x $numberOfPhecode

        $fullList = [];

        $icd9List = [];

        for ($i = 0; $i < $numberOfICD9; $i++) {
            $icd9Code = strtoupper($prefix . $this->faker->unique()->word . $suffix);
            $icd9Description = $this->faker->sentence;

            $icd9List[$icd9Code] = $icd9Description;

            DB::connection(env('DB_CONNECTION'))->table('icd9')->insert([
                [
                    'code' => $icd9Code,
                    'description' => $icd9Description
                ]
            ]);
        }

        $phecodeList = [];

        for ($i = 0; $i < $numberOfPhecode; $i++) {
            $phecode = strtoupper($prefix . $this->faker->unique()->word . $suffix);
            $phecodeDescription = $this->faker->sentence;

            $phecodeList[$phecode] = $phecodeDescription;

            DB::connection(env('DB_CONNECTION'))->table('phecode')->insert([
                [
                    'code' => $phecode,
                    'description' => $phecodeDescription
                ]
            ]);
        }

        foreach ($phecodeList as $phecode => $phecodeDescription) {
            foreach ($icd9List as $icd9 => $icd9Description) {
                DB::connection(env('DB_CONNECTION'))->table('icd9_phecode')->insert([
                    'icd9' => $icd9,
                    'phecode' => $phecode
                ]);

                $fullList[] = [
                    'icd9' => $icd9,
                    'icd9Description' => $icd9Description,
                    'phecode' => $phecode,
                    'phecodeDescription' => $phecodeDescription,
                ];
            }
        }

        return $fullList;
    }

    public function generateICD9(
        int $numberOfICD9 = 1,
        string $prefix = '',
        string $suffix = ''
    ) {
        $icd9List = [];

        for ($i = 0; $i < $numberOfICD9; $i++) {
            $icd9Code = strtoupper($prefix . $this->faker->unique()->word . $suffix);
            $icd9Description = $this->faker->sentence;

            $icd9List[] = [
                'icd9' => $icd9Code,
                'icd9Description' => $icd9Description
            ];

            DB::connection(env('DB_CONNECTION'))->table('icd9')->insert([
                [
                    'code' => $icd9Code,
                    'description' => $icd9Description
                ]
            ]);
        }

        return $icd9List;
    }

    public function generatePhecode(
        int $numberOfPhecode = 1,
        string $prefix = '',
        string $suffix = ''
    ) {
        $phecodeList = [];

        for ($i = 0; $i < $numberOfPhecode; $i++) {
            $phecode = strtoupper($prefix . $this->faker->unique()->word . $suffix);
            $phecodeDescription = null;

            $phecodeList[] = [
                'phecode' => $phecode,
                'phecodeDescription' => $phecodeDescription
            ];

            DB::connection(env('DB_CONNECTION'))->table('phecode')->insert([
                [
                    'code' => $phecode,
                    'description' => $phecodeDescription
                ]
            ]);
        }

        return $phecodeList;
    }
}
