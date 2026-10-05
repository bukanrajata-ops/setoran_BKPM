<?php
namespace App\Models;
use App\Core\Model;
use PDO;
class Matakuliah extends Model
{
    public static function all(): array
    {
        $s=self::db()->query("SELECT mk.*, p.nama AS prodi_nama FROM matakuliah mk JOIN prodi p ON mk.prodi_id=p.id ORDER BY mk.kode ASC");
        return $s->fetchAll(PDO::FETCH_ASSOC);
    }
public static function find(int $id): ?array
{
    $s=self::db()->prepare("SELECT * FROM matakuliah WHERE id=:id");
    $s->execute(['id'=>$id]);
    return $s->fetch(PDO::FETCH_ASSOC) ?: null;
}
public static function create(string $kode,string $nama,int $sks,int $prodi_id): bool
{
    $s=self::db()->prepare("INSERT INTO matakuliah (kode,nama,sks,prodi_id) VALUES (:kode,:nama,:sks,:prodi_id)");
    return $s->execute(compact('kode','nama','sks','prodi_id'));
}
public static function update(int $id,string $kode,string $nama,int $sks,int $prodi_id): bool
{
    $s=self::db()->prepare("UPDATE matakuliah SET kode=:kode,nama=:nama,sks=:sks,prodi_id=:prodi_id WHERE id=:id");
    return $s->execute(compact('id','kode','nama','sks','prodi_id'));
}
public static function delete(int $id): bool
{
    $s=self::db()->prepare("DELETE FROM matakuliah WHERE id=:id");
    return $s->execute(['id'=>$id]);
}
}
