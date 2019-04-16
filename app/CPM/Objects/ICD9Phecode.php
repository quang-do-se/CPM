<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/10/19
 * Time: 4:24 PM
 */

namespace App\CPM\Objects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class ICD9Phecode implements Jsonable, Arrayable
{
    private $phecode;
    private $phecodeDescription;

    private $icd9;
    private $icd9Description;

    public function __construct(
        string $icd9,
        string $phecode,
        string $icd9Description = '',
        string $phecodeDescription = ''
    ) {
        $this->phecode = $phecode;
        $this->phecodeDescription = $phecodeDescription;
        $this->icd9 = $icd9;
        $this->icd9Description = $icd9Description;
    }

    public function getPhecode()
    {
        return $this->phecode;
    }

    public function getPhecodeDescription()
    {
        return $this->phecodeDescription;
    }

    public function getICD9()
    {
        return $this->icd9;
    }

    public function getICD9Description()
    {
        return $this->icd9Description;
    }

    public function toArray()
    {
        return [
            'icd9' => $this->icd9,
            'phecode' => $this->phecode,
            'icd9Description' => $this->icd9Description,
            'phecodeDescription' => $this->phecodeDescription,
        ];
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }
}
