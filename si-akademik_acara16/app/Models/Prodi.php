<?php
namespace App\Models;
use App\Core\BaseModel;
use PDO;
class Prodi extends BaseModel
{
    public static function all(): array
    {
        return self::db()->query("SELECT * FROM prodi ORDER BY kode ASC")->fetchAll(PDO::FETCH_ASSOC);
    }
public static function find(int $id): ?array
{
    $s=self::db()->prepare("SELECT * FROM prodi WHERE id=:id");
    $s->execute(['id'=>$id]);
    return $s->fetch(PDO::FETCH_ASSOC) ?: null;
}
public static function create(string $kode,string $nama): bool
{
    $s=self::db()->prepare("INSERT INTO prodi (kode,nama) VALUES (:kode,:nama)");
    return $s->execute(['kode'=>$kode,'nama'=>$nama]);
}
public static function update(int $id,string $kode,string $nama): bool
{
    $s=self::db()->prepare("UPDATE prodi SET kode=:kode,nama=:nama WHERE id=:id");
    return $s->execute(['id'=>$id,'kode'=>$kode,'nama'=>$nama]);
}
public static function delete(int $id): bool
{
    $s=self::db()->prepare("DELETE FROM prodi WHERE id=:id");
    return $s->execute(['id'=>$id]);
}
}
