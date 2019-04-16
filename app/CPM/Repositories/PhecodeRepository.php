<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 9:14 AM
 */

namespace App\CPM\Repositories;

use App\CPM\Collections\PhecodeCollection;
use App\CPM\Objects\Phecode;

interface PhecodeRepository
{
    const LIMIT = 1000;

    public function getAll(
        int $offset = 0,
        int $limit = self::LIMIT
    ): PhecodeCollection;

    public function get(string $code,
                        bool $typeahead = false,
                        int $offset = 0,
                        int $limit = self::LIMIT
    ): PhecodeCollection;

    public function add(Phecode $phecode): bool;
    public function update(Phecode $phecode): int;
    public function addOrUpdate(Phecode $phecode): bool;
    public function delete(string $code): int;
}
