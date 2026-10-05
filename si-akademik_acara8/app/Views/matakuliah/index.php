<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">DATA MATA KULIAH</h4>
        <a href="<?=BASE_URL?>/matakuliah/create" class="btn btn-light btn-sm">+ Tambah Mata Kuliah</a>
    </div>
<div class="card-body">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>SKS</th>
                <th>Prodi</th>
                <th>Aksi</th>
            </tr>
    </thead>
<tbody><?php foreach($matakuliahList as $mk):?><tr>
        <td><?=htmlspecialchars($mk['kode'])?></td>
        <td><?=htmlspecialchars($mk['nama'])?></td>
        <td><?=htmlspecialchars($mk['sks'])?></td>
        <td><?=htmlspecialchars($mk['prodi_nama'])?></td>
        <td>
            <a class="btn btn-sm btn-warning" href="<?=BASE_URL?>/matakuliah/<?=$mk['id']?>/edit">Edit</a>
            <form class="d-inline" method="post" action="<?=BASE_URL?>/matakuliah/<?=$mk['id']?>/delete" data-confirm="Hapus mata kuliah ini?">
                <button class="btn btn-sm btn-danger">Hapus</button>
            </form>
    </td>
</tr><?php endforeach;?></tbody>
</table>
</div>
</div>
