<div class="card shadow-sm" style="max-width:600px">
    <div class="card-header bg-info text-white">
        <h4 class="mb-0">Detail Mahasiswa</h4>
    </div>
<div class="card-body"><?php if($mahasiswaDitemukan):?><table class="table table-borderless">
        <tr>
            <th>NIM</th>
            <td><?=htmlspecialchars($mahasiswaDitemukan->getNim())?></td>
        </tr>
    <tr>
        <th>Nama</th>
        <td><?=htmlspecialchars($mahasiswaDitemukan->getNama())?></td>
    </tr>
<tr>
    <th>Prodi</th>
    <td><?=htmlspecialchars($mahasiswaDitemukan->getProdi())?></td>
</tr>
<tr>
    <th>Angkatan</th>
    <td><?=htmlspecialchars($mahasiswaDitemukan->getAngkatan())?></td>
</tr>
<tr>
    <th>Status</th>
    <td><?=htmlspecialchars(ucfirst($mahasiswaDitemukan->getStatus()))?></td>
</tr>
</table>
<div class="d-flex gap-2">
    <a href="<?=BASE_URL?>/mahasiswa/<?=urlencode($mahasiswaDitemukan->getNim())?>/edit" class="btn btn-warning">Edit</a>
    <a href="<?=BASE_URL?>/mahasiswa" class="btn btn-secondary">Kembali</a>
</div><?php else:?><p class="text-danger">Mahasiswa tidak ditemukan.</p>
<a href="<?=BASE_URL?>/mahasiswa" class="btn btn-secondary">Kembali</a><?php endif;?></div>
</div>
