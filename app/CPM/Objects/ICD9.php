<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/12/19
 * Time: 9:30 AM
 */

namespace App\CPM\Objects;

use Illuminate\Contracts\Support\Arrayable;

class ICD9 implements Arrayable
{
    private $code;
    private $description;

    public function __construct(string $code, string $description = '')
    {
        $this->code = strtoupper($code);
        $this->description = $description;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'description' => $this->description
        ];
    }
}
