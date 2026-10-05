<?php
namespace App\Models;
use App\Core\Model;
use PDO;
class Mahasiswa extends Model
{
    private string $nim;
    private string $nama;
    private string $prodi;
    private string $angkatan;
    private string $status;
    public function __construct(
    string $nim,
    string $nama,
    string $prodi,
    string $angkatan = '',
    string $status = 'aktif'
    ) {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->prodi = $prodi;
        $this->status = $status;
        $this->angkatan = $angkatan !== ''
        ? $angkatan
        : ('20' . substr($nim, 0, 2));
    }
public function getNim(): string
{
    return $this->nim;
}
public function getNama(): string
{
    return $this->nama;
}
public function getProdi(): string
{
    return $this->prodi;
}
public function getAngkatan(): string
{
    return $this->angkatan;
}
public function getStatus(): string
{
    return $this->status;
}
public function getLabel(): string
{
    return "{$this->nim} - {$this->nama} ({$this->prodi})";
}
public static function all(string $keyword = ''): array
{
    if ($keyword !== '') {
        $stmt = self::db()->prepare(
        "SELECT m.nim, m.nama, m.angkatan, m.status,
        p.nama AS prodi_nama
        FROM mahasiswa m
        JOIN prodi p ON m.prodi_id = p.id
        WHERE m.nim LIKE :keyword
        OR m.nama LIKE :keyword
        ORDER BY m.nim ASC"
        );
        $stmt->execute([
        'keyword' => '%' . $keyword . '%'
        ]);
    } else {
    $stmt = self::db()->query(
    "SELECT m.nim, m.nama, m.angkatan, m.status,
    p.nama AS prodi_nama
    FROM mahasiswa m
    JOIN prodi p ON m.prodi_id = p.id
    ORDER BY m.nim ASC"
    );
}
$daftar = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $daftar[] = new self(
    $row['nim'],
    $row['nama'],
    $row['prodi_nama'],
    (string) $row['angkatan'],
    $row['status']
    );
}
return $daftar;
}
public static function findByNimData(string $nim): ?array
{
    $s = self::db()->prepare(
    "SELECT * FROM mahasiswa WHERE nim = :nim"
    );
    $s->execute([
    'nim' => $nim
    ]);
    return $s->fetch(PDO::FETCH_ASSOC) ?: null;
}
public static function findByNim(string $nim): ?self
{
    $s = self::db()->prepare(
    "SELECT m.nim, m.nama, m.angkatan, m.status,
    p.nama AS prodi_nama
    FROM mahasiswa m
    JOIN prodi p ON m.prodi_id = p.id
    WHERE m.nim = :nim"
    );
    $s->execute([
    'nim' => $nim
    ]);
    $row = $s->fetch(PDO::FETCH_ASSOC);
    return $row
    ? new self(
    $row['nim'],
    $row['nama'],
    $row['prodi_nama'],
    (string) $row['angkatan'],
    $row['status']
    )
    : null;
}
public static function create(
string $nim,
string $nama,
string $email,
int $prodi_id,
int $angkatan,
string $status = 'aktif'
): bool {
    $s = self::db()->prepare(
    "INSERT INTO mahasiswa
    (nim, nama, email, prodi_id, angkatan, status)
    VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)"
    );
    return $s->execute(
    compact(
    'nim',
    'nama',
    'email',
    'prodi_id',
    'angkatan',
    'status'
    )
    );
}
public static function updateByNim(
string $oldNim,
string $nim,
string $nama,
string $email,
int $prodi_id,
int $angkatan,
string $status
): bool {
    $s = self::db()->prepare(
    "UPDATE mahasiswa
    SET nim = :nim,
    nama = :nama,
    email = :email,
    prodi_id = :prodi_id,
    angkatan = :angkatan,
    status = :status
    WHERE nim = :old_nim"
    );
    return $s->execute([
    'old_nim' => $oldNim,
    'nim' => $nim,
    'nama' => $nama,
    'email' => $email,
    'prodi_id' => $prodi_id,
    'angkatan' => $angkatan,
    'status' => $status
    ]);
}
public static function deleteByNim(string $nim): bool
{
    $s = self::db()->prepare(
    "DELETE FROM mahasiswa WHERE nim = :nim"
    );
    return $s->execute([
    'nim' => $nim
    ]);
}
}
