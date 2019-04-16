<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 9:46 AM
 */

namespace App\CPM\Repositories;

use App\CPM\Collections\ICD9Collection;
use App\CPM\Objects\ICD9;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ICD9RepositorySQL implements ICD9Repository
{
    private function getTable(): Builder
    {
        return DB
            ::connection(config('database.default'))
            ->table('icd9');
    }

    public function getAll(
        int $offset = 0,
        int $limit = self::LIMIT
    ): ICD9Collection
    {
        $records = $this->getTable()->offset($offset)->limit($limit)->get();

        $collection = new ICD9Collection();

        foreach ($records as $record) {
            $collection->add(
                new ICD9($record->code, $record->description)
            );
        }

        return $collection;
    }

    public function get(
        string $code,
        bool $typeahead = false,
        int $offset = 0,
        int $limit = self::LIMIT
    ): ICD9Collection
    {
        $query = $this->getTable();

        $code = strtoupper($code);

        if ($typeahead) {
            $query->whereRaw('UPPER(code) LIKE ?', ["%$code%"]);
        } else {
            $query->where('code', $code);
        }

        $records = $query->get();

        $collection = new ICD9Collection();

        foreach ($records as $record) {
            $collection->add(
                new ICD9($record->code, $record->description)
            );
        }

        return $collection;
    }

    public function add(ICD9 $icd9): bool
    {
        try {
            return $this->getTable()
                ->insert([
                    'code' => $icd9->getCode(),
                    'description' => $icd9->getDescription()
                ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function update(ICD9 $icd9): int
    {
        return $this->getTable()
            ->where('code', $icd9->getCode())
            ->update([
                'description' => $icd9->getDescription()
            ]);
    }

    public function delete(string $code): int
    {
        return $this->getTable()->where('code', $code)->delete();
    }

    public function addOrUpdate(ICD9 $icd9): bool
    {
        return $this->add($icd9) ? true : $this->update($icd9);
    }
}
