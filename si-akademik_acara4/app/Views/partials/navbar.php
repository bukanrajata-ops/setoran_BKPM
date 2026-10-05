<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link <?= ($active ?? '') === 'mahasiswa' ? 'active' : '' ?>" href="?url=Mahasiswa">Data Mahasiswa</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($active ?? '') === 'dosen' ? 'active' : '' ?>" href="?url=Dosen">Data Dosen</a>
    </li>
</ul>
