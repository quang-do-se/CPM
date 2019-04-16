<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/10/19
 * Time: 10:01 AM
 */

namespace App\CPM\Repositories;

use App\CPM\Collections\ICD9PhecodeCollection;
use App\CPM\Objects\ICD9Phecode;

interface ICD9PhecodeRepository
{
    const LIMIT = 1000;

    public function getByPhecode(
        string $phecode,
        bool $typeahead = false,
        int $offset = 0,
        int $limit = self::LIMIT
    ): ICD9PhecodeCollection;

    public function getByICD9(
        string $icd9,
        bool $typeahead = false,
        int $offset = 0,
        int $limit = self::LIMIT
    ): ICD9PhecodeCollection;

    public function getAll(
        int $offset = 0,
        int $limit = self::LIMIT
    ): ICD9PhecodeCollection;

    public function add(ICD9Phecode $icd9Phecode): bool;

    public function delete(ICD9Phecode $icd9Phecode): int;
}
