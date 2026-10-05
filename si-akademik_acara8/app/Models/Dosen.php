<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Dosen extends Model
{
    public static function getAll(): array
    {
        $stmt = self::db()->query(
            "SELECT
                nidn,
                nama,
                bidang_keahlian AS matkul
             FROM dosen
             ORDER BY nidn ASC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}