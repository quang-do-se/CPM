<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 9:13 AM
 */

namespace App\CPM\Repositories;

use App\CPM\Collections\ICD9Collection;
use App\CPM\Objects\ICD9;

interface ICD9Repository
{
    const LIMIT = 1000;

    public function getAll(
        int $offset = 0,
        int $limit = self::LIMIT
    ): ICD9Collection;

    public function get(
        string $code,
        bool $typeahead = false,
        int $offset = 0,
        int $limit = self::LIMIT
    ): ICD9Collection;

    public function add(ICD9 $icd9): bool;
    public function update(ICD9 $icd9): int;
    public function addOrUpdate(ICD9 $icd9): bool;
    public function delete(string $code): int;
}
