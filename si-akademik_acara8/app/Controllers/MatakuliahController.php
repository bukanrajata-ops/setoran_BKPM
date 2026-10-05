<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Matakuliah;
use App\Models\Prodi;
use PDOException;
class MatakuliahController extends Controller
{
    private function redirect(string $p):void{
        header('Location: '.BASE_URL.$p);
        exit;
    }
private function flash(string $t,string $x):void{
    if(session_status()===PHP_SESSION_NONE)session_start();
    $_SESSION['flash_message']=['type'=>$t,'text'=>$x];
}
public function index():void{
    $this->render(__DIR__.'/../Views/matakuliah/index.php',['matakuliahList'=>Matakuliah::all()],'matakuliah');
}
public function create():void{
    $this->render(__DIR__.'/../Views/matakuliah/create.php',['prodiList'=>Prodi::all(),'error'=>null],'matakuliah');
}
public function store():void{
    $kode=strtoupper(trim($_POST['kode']??''));
    $nama=trim($_POST['nama']??'');
    $sks=(int)($_POST['sks']??0);
    $prodi_id=(int)($_POST['prodi_id']??0);
    if($kode===''||$nama===''||$sks<=0||$prodi_id<=0){
        $this->render(__DIR__.'/../Views/matakuliah/create.php',['prodiList'=>Prodi::all(),'error'=>'Semua data wajib diisi dengan benar.'],'matakuliah');
        return;
    }
try{
    Matakuliah::create($kode,$nama,$sks,$prodi_id);
    $this->flash('success','Mata kuliah berhasil ditambahkan.');
    $this->redirect('/matakuliah');
} catch (PDOException $e){
    $this->render(__DIR__.'/../Views/matakuliah/create.php',['prodiList'=>Prodi::all(),'error'=>'Kode mata kuliah sudah digunakan.'],'matakuliah');
}
}
public function edit($id):void{
    $data=Matakuliah::find((int)$id);
    if(!$data){
        $this->flash('danger','Mata kuliah tidak ditemukan.');
        $this->redirect('/matakuliah');
    }
$this->render(__DIR__.'/../Views/matakuliah/edit.php',['matakuliah'=>$data,'prodiList'=>Prodi::all(),'error'=>null],'matakuliah');
}
public function update($id):void{
    $kode=strtoupper(trim($_POST['kode']??''));
    $nama=trim($_POST['nama']??'');
    $sks=(int)($_POST['sks']??0);
    $prodi_id=(int)($_POST['prodi_id']??0);
    if($kode===''||$nama===''||$sks<=0||$prodi_id<=0){
        $this->flash('danger','Semua data wajib diisi dengan benar.');
        $this->redirect('/matakuliah/'.$id.'/edit');
    }
try{
    Matakuliah::update((int)$id,$kode,$nama,$sks,$prodi_id);
    $this->flash('success','Mata kuliah berhasil diubah.');
    $this->redirect('/matakuliah');
} catch (PDOException $e){
    $this->flash('danger','Gagal mengubah mata kuliah.');
    $this->redirect('/matakuliah');
}
}
public function delete($id):void{
    try{
        Matakuliah::delete((int)$id);
        $this->flash('success','Mata kuliah berhasil dihapus.');
    } catch (PDOException $e){
    $this->flash('danger','Mata kuliah gagal dihapus.');
}
$this->redirect('/matakuliah');
}
}
