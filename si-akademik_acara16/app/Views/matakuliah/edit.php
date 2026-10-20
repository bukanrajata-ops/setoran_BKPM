<div class="card shadow-sm">
    <div class="card-header bg-warning">
        <h4>Edit Mata Kuliah</h4>
    </div>
<div class="card-body"><?php if($error):?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif;?><form method="post" action="<?=BASE_URL?>/matakuliah/<?=$matakuliah['id']?>/update">
        <div class="mb-3">
            <label class="form-label">Kode</label>
            <input name="kode" class="form-control" value="<?=htmlspecialchars($matakuliah['kode'])?>" required>
        </div>
    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input name="nama" class="form-control" value="<?=htmlspecialchars($matakuliah['nama'])?>" required>
    </div>
<div class="mb-3">
    <label class="form-label">SKS</label>
    <input type="number" name="sks" class="form-control" min="1" max="6" value="<?=htmlspecialchars($matakuliah['sks'])?>" required>
</div>
<div class="mb-3">
    <label class="form-label">Prodi</label>
    <select name="prodi_id" class="form-select" required><?php foreach($prodiList as $p):?><option value="<?=$p['id']?>" <?=((int)$matakuliah['prodi_id']===(int)$p['id'])?'selected':''?>><?=htmlspecialchars($p['nama'])?></option><?php endforeach;?></select>
</div>
<button class="btn btn-warning">Simpan</button>
<a class="btn btn-secondary" href="<?=BASE_URL?>/matakuliah">Kembali</a>
</form>
</div>
</div>
