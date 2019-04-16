<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 4:08 PM
 */

namespace App\CPM\Repositories;

use App\CPM\Collections\PhecodeCollection;
use App\CPM\Objects\Phecode;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class PhecodeRepositorySQL implements PhecodeRepository
{
    private function getTable(): Builder
    {
        return DB
            ::connection(config('database.default'))
            ->table('phecode');
    }

    public function get
    (
        string $code,
        bool $typeahead = false,
        int $offset = 0,
        int $limit = self::LIMIT
    ): PhecodeCollection
    {
        $query = $this->getTable();

        $code = strtoupper($code);

        if ($typeahead) {
            $query->whereRaw('UPPER(code) LIKE ?', ["%$code%"]);
        } else {
            $query->where('code', $code);
        }

        $records = $query->get();

        $collection = new PhecodeCollection();

        foreach ($records as $record) {
            $collection->add(
                new Phecode($record->code, $record->description)
            );
        }

        return $collection;
    }

    public function getAll(
        int $offset = 0,
        int $limit = self::LIMIT
    ): PhecodeCollection
    {
        $records = $this->getTable()->offset($offset)->limit($limit)->get();

        $collection = new PhecodeCollection();

        foreach ($records as $record) {
            $collection->add(
                new Phecode($record->code, $record->description)
            );
        }

        return $collection;
    }

    public function add(Phecode $phecode): bool
    {
        try {
            return $this->getTable()
                ->insert([
                    'code' => $phecode->getCode(),
                    'description' => $phecode->getDescription()
                ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function update(Phecode $phecode): int
    {
        return $this->getTable()
            ->where('code', $phecode->getCode())
            ->update([
                'description' => $phecode->getDescription()
            ]);
    }

    public function delete(string $code): int
    {
        return $this->getTable()->where('code', $code)->delete();
    }

    public function addOrUpdate(Phecode $phecode): bool
    {
        return $this->add($phecode) ? true : $this->update($phecode);
    }
}
