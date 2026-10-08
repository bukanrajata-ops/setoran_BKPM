<div class="card shadow-sm">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Edit Mahasiswa</h4>
    </div>
<div class="card-body"><?php if($error):?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?><form method="post" action="<?=BASE_URL?>/mahasiswa/<?=urlencode($originalNim ?? $mahasiswa['nim'] ?? '')?>/update">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">NIM</label>
                <input name="nim" class="form-control" value="<?=htmlspecialchars($mahasiswa['nim']??'')?>" required>
            </div>
        <div class="col-md-6">
            <label class="form-label">Nama</label>
            <input name="nama" class="form-control" value="<?=htmlspecialchars($mahasiswa['nama']??'')?>" required>
        </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?=htmlspecialchars($mahasiswa['email']??'')?>" required>
    </div>
<div class="col-md-6">
    <label class="form-label">Prodi</label>
    <select name="prodi_id" class="form-select" required><?php foreach($prodiList as $p):?><option value="<?=$p['id']?>" <?=((int)($mahasiswa['prodi_id']??0)===(int)$p['id'])?'selected':''?>><?=htmlspecialchars($p['nama'])?></option><?php endforeach;?></select>
</div>
<div class="col-md-6">
    <label class="form-label">Angkatan</label>
    <input type="number" name="angkatan" class="form-control" value="<?=htmlspecialchars($mahasiswa['angkatan']??'')?>" required>
</div>
<div class="col-md-6">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
        <option value="aktif" <?=($mahasiswa['status']??'')==='aktif'?'selected':''?>>Aktif</option>
        <option value="cuti" <?=($mahasiswa['status']??'')==='cuti'?'selected':''?>>Cuti</option>
        <option value="lulus" <?=($mahasiswa['status']??'')==='lulus'?'selected':''?>>Lulus</option>
    </select>
</div>
</div>
<div class="mt-4">
    <button class="btn btn-warning">Simpan Perubahan</button>
    <a href="<?=BASE_URL?>/mahasiswa" class="btn btn-secondary">Kembali</a>
</div>
</form>
</div>
</div>
