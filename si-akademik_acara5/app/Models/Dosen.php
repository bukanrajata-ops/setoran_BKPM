<?php
// app/Models/Dosen.php
namespace App\Models;

class Dosen
{
    public static function getAll(): array
    {
        return [
            ['nidn' => '0028069702', 'nama' => 'Ulfa Emi Rahmawati, S.Kom., M.Kom.',  'matkul' => 'Literasi Digital'],
            ['nidn' => '0009059403', 'nama' => 'Qonitatul Hasanah, S.ST., M.Tr.T',     'matkul' => 'Workshop Sistem Informasi Web Server'],
            ['nidn' => '0009109304', 'nama' => 'Raditya Arief Pratama, S.Kom., M.Eng', 'matkul' => 'Workshop Mobile Applications Advance'],
        ];
    }
}
