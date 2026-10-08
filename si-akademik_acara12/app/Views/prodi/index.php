<div class="card shadow-sm">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">DATA PRODI</h4>
        <a href="<?=BASE_URL?>/prodi/create" class="btn btn-light btn-sm">+ Tambah Prodi</a>
    </div>
<div class="card-body">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Prodi</th>
                <th>Aksi</th>
            </tr>
    </thead>
<tbody><?php foreach($prodiList as $p):?><tr>
        <td><?=htmlspecialchars($p['kode'])?></td>
        <td><?=htmlspecialchars($p['nama'])?></td>
        <td>
            <a class="btn btn-sm btn-warning" href="<?=BASE_URL?>/prodi/<?=$p['id']?>/edit">Edit</a>
            <form class="d-inline" method="post" action="<?=BASE_URL?>/prodi/<?=$p['id']?>/delete" onsubmit="return confirm('Hapus prodi ini?')">
                <button class="btn btn-sm btn-danger">Hapus</button>
            </form>
    </td>
</tr><?php endforeach;?></tbody>
</table>
</div>
</div>
