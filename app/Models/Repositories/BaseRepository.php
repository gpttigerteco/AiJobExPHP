<?php

namespace App\Models\Repositories;

use App\Core\DB;
use PDO;

abstract class BaseRepository
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::pdo();
    }
}
