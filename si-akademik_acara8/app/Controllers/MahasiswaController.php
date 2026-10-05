<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use PDOException;
class MahasiswaController extends Controller
{
    private function redirect(string $path): void {
        header('Location: '.BASE_URL.$path);
        exit;
    }
private function flash(string $type,string $text): void {
    if(session_status()===PHP_SESSION_NONE) session_start();
    $_SESSION['flash_message']=['type'=>$type,'text'=>$text];
}
public function index(): void
{
    $keyword=trim($_GET['q'] ?? '');
    $this->render(__DIR__.'/../Views/mahasiswa/index.php', ['daftarMahasiswa'=>Mahasiswa::all($keyword),'keyword'=>$keyword], 'mahasiswa');
}
public function create(): void
{
    $this->render(__DIR__.'/../Views/mahasiswa/create.php',['prodiList'=>Prodi::all(),'error'=>null],'mahasiswa');
}
public function store(): void
{
    $nim=trim($_POST['nim']??'');
    $nama=trim($_POST['nama']??'');
    $email=trim($_POST['email']??'');
    $prodi_id=(int)($_POST['prodi_id']??0);
    $angkatan=(int)($_POST['angkatan']??date('Y'));
    $status=$_POST['status']??'aktif';
    if($nim===''||$nama===''||$email===''||$prodi_id<=0||!preg_match('/^\d+$/',$nim)) {
        $this->render(__DIR__.'/../Views/mahasiswa/create.php',['prodiList'=>Prodi::all(),'error'=>'Data belum lengkap atau NIM harus berupa angka.'],'mahasiswa');
        return;
    }
try {
    Mahasiswa::create($nim,$nama,$email,$prodi_id,$angkatan,$status);
    $this->flash('success','Data mahasiswa berhasil ditambahkan.');
    $this->redirect('/mahasiswa');
} catch (PDOException $e){
    $this->render(__DIR__.'/../Views/mahasiswa/create.php',['prodiList'=>Prodi::all(),'error'=>'Gagal menyimpan. Pastikan NIM belum digunakan.'],'mahasiswa');
}
}
public function edit($id): void
{
    $data=Mahasiswa::findByNimData((string)$id);
    if(!$data){
        $this->flash('danger','Data mahasiswa tidak ditemukan.');
        $this->redirect('/mahasiswa');
    }
$this->render(__DIR__.'/../Views/mahasiswa/edit.php',['mahasiswa'=>$data,'originalNim'=>$id,'prodiList'=>Prodi::all(),'error'=>null],'mahasiswa');
}
public function update($id): void
{
    $oldNim=(string)$id;
    $nim=trim($_POST['nim']??'');
    $nama=trim($_POST['nama']??'');
    $email=trim($_POST['email']??'');
    $prodi_id=(int)($_POST['prodi_id']??0);
    $angkatan=(int)($_POST['angkatan']??date('Y'));
    $status=$_POST['status']??'aktif';
    if($nim===''||$nama===''||$email===''||$prodi_id<=0||!preg_match('/^\d+$/',$nim)){
        $this->render(__DIR__.'/../Views/mahasiswa/edit.php',['mahasiswa'=>array_merge(Mahasiswa::findByNimData($oldNim)??[],$_POST),'prodiList'=>Prodi::all(),'error'=>'Data belum lengkap atau NIM harus berupa angka.'],'mahasiswa');
        return;
    }
try {
    Mahasiswa::updateByNim($oldNim,$nim,$nama,$email,$prodi_id,$angkatan,$status);
    $this->flash('success','Data mahasiswa berhasil diubah.');
    $this->redirect('/mahasiswa');
} catch (PDOException $e){
    $this->flash('danger','Gagal mengubah data. Pastikan NIM unik.');
    $this->redirect('/mahasiswa');
}
}
public function delete($id): void
{
    try {
        Mahasiswa::deleteByNim((string)$id);
        $this->flash('success','Data mahasiswa berhasil dihapus.');
    } catch (PDOException $e){
    $this->flash('danger','Data mahasiswa gagal dihapus.');
}
$this->redirect('/mahasiswa');
}
public function show($nim): void
{
    $this->render(__DIR__.'/../Views/mahasiswa/detail.php',['mahasiswaDitemukan'=>Mahasiswa::findByNim($nim)],'mahasiswa');
}
}
