<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/11/19
 * Time: 2:55 PM
 */

namespace App\CPM\Repositories;

use App\CPM\Collections\ICD9PhecodeCollection;
use App\CPM\Objects\ICD9;
use App\CPM\Objects\ICD9Phecode;
use App\CPM\Objects\Phecode;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ICD9PhecodeRepositorySQL implements ICD9PhecodeRepository
{
    private $icd9Repository;
    private $phecodeRepository;

    public function __construct(
        ICD9Repository $icd9Repository,
        PhecodeRepository $phecodeRepository
    ) {
        $this->icd9Repository = $icd9Repository;
        $this->phecodeRepository = $phecodeRepository;
    }

    private function getConnection(): ConnectionInterface
    {
        return DB
            ::connection(config('database.default'));
    }

    private function getMainTable(): Builder
    {
        return DB
            ::connection(config('database.default'))
            ->table('icd9_phecode');
    }

    public function getByPhecode(
        string $phecode,
        bool $typeahead = false,
        int $offset = 0,
        int $limit = self::LIMIT
    ): ICD9PhecodeCollection {
        $query = $this->getConnection()
            ->table('phecode')
            ->leftJoin('icd9_phecode', 'phecode.code', 'icd9_phecode.phecode')
            ->leftJoin('icd9', 'icd9_phecode.icd9', 'icd9.code')
            ->select(
                'phecode.code as phecode',
                'phecode.description as phecode_description',
                'icd9.code as icd9',
                'icd9.description as icd9_description'
            );

        $phecode = strtoupper($phecode);

        if ($typeahead) {
            $query->whereRaw('UPPER(phecode.code) LIKE ?', ["%$phecode%"]);
        } else {
            $query->where('phecode.code', $phecode);
        }

        $query->offset($offset)->limit($limit);

        $searchedRecords = $query->get();

        $collection = new ICD9PhecodeCollection();

        foreach ($searchedRecords as $record) {
            $codeDescription = new ICD9Phecode(
                $record->icd9 ?? '',
                $record->phecode,
                $record->icd9_description ?? '',
                $record->phecode_description ?? ''
            );

            $collection->add($codeDescription);
        }

        return $collection;
    }

    public function getByICD9(
        string $icd9,
        bool $typeahead = false,
        int $offset = 0,
        int $limit = self::LIMIT
    ): ICD9PhecodeCollection {
        $query = $this->getConnection()
            ->table('icd9')
            ->leftJoin('icd9_phecode', 'icd9.code', 'icd9_phecode.icd9')
            ->leftJoin('phecode', 'icd9_phecode.phecode', 'phecode.code')
            ->select(
                'phecode.code as phecode',
                'phecode.description as phecode_description',
                'icd9.code as icd9',
                'icd9.description as icd9_description'
            );

        $icd9 = strtoupper($icd9);

        if ($typeahead) {
            $query->whereRaw('UPPER(icd9.code) LIKE ?', ["%$icd9%"]);
        } else {
            $query->where('icd9.code', $icd9);
        }

        $query->offset($offset)->limit($limit);

        $searchedRecords = $query->get();

        $collection = new ICD9PhecodeCollection();

        foreach ($searchedRecords as $record) {
            $codeDescription = new ICD9Phecode(
                $record->icd9,
                $record->phecode ?? '',
                $record->icd9_description ?? '',
                $record->phecode_description ?? ''
            );

            $collection->add($codeDescription);
        }

        return $collection;
    }

    public function getAll(
        int $offset = 0,
        int $limit = self::LIMIT
    ): ICD9PhecodeCollection {
        $query = $this->getMainTable()
            ->leftJoin('icd9', 'icd9_phecode.icd9', 'icd9.code')
            ->leftJoin('phecode', 'icd9_phecode.phecode', 'phecode.code')
            ->select(
                'phecode.code as phecode',
                'phecode.description as phecode_description',
                'icd9.code as icd9',
                'icd9.description as icd9_description'
            );
        ;

        $records = $query->get();

        $collection = new ICD9PhecodeCollection();

        foreach ($records as $record) {
            $codeDescription = new ICD9Phecode(
                $record->icd9,
                $record->phecode,
                $record->icd9_description,
                $record->phecode_description
            );

            $collection->add($codeDescription);
        }

        return $collection;
    }

    public function add(ICD9Phecode $icd9Phecode): bool
    {
        try {
            $this->icd9Repository->add(
                new ICD9(
                    $icd9Phecode->getICD9(),
                    $icd9Phecode->getICD9Description()
                )
            );

            $this->phecodeRepository->add(
                new Phecode(
                    $icd9Phecode->getPhecode(),
                    $icd9Phecode->getPhecodeDescription()
                )
            );

            return $this->getMainTable()->insert([
                'icd9' => $icd9Phecode->getICD9(),
                'phecode' => $icd9Phecode->getPhecode()
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete(ICD9Phecode $icd9Phecode): int
    {
        return $this->getMainTable()
            ->where('icd9', $icd9Phecode->getICD9())
            ->where('phecode', $icd9Phecode->getPhecode())
            ->delete();
    }
}
