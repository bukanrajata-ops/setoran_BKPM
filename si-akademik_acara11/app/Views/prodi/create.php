<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h4>Tambah Prodi</h4>
    </div>
<div class="card-body"><?php if($error):?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif;?><form method="post" action="<?=BASE_URL?>/prodi">
        <label class="form-label">Kode</label>
        <input name="kode" class="form-control mb-3" maxlength="10" required>
        <label class="form-label">Nama Prodi</label>
        <input name="nama" class="form-control" required>
        <div class="mt-3">
            <button class="btn btn-success">Simpan</button>
            <a class="btn btn-secondary" href="<?=BASE_URL?>/prodi">Kembali</a>
        </div>
</form>
</div>
</div>
