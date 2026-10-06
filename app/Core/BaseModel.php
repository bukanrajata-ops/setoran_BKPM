<?php
// app/Core/BaseModel.php
// Base Model (Acara 10 - Inheritance): menyediakan akses PDO untuk semua
// Model yang membutuhkan query langsung. Koneksi dibuat oleh class
// Database (Acara 9) dan dipakai bersama (satu koneksi per request),
// sehingga tidak ada lagi kode koneksi yang ditulis ulang di sini.
namespace App\Core;

use PDO;

abstract class BaseModel
{
    private static ?Database $database = null;

    protected static function db(): PDO
    {
        if (self::$database === null) {
            self::$database = new Database();
        }

        return self::$database->getConnection();
    }
}
