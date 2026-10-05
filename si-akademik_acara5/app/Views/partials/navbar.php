<!-- app/Views/partials/navbar.php -->
<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link <?= ($active ?? '') === 'mahasiswa' ? 'active' : '' ?>" href="<?= BASE_URL ?>/mahasiswa">Data Mahasiswa</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($active ?? '') === 'dosen' ? 'active' : '' ?>" href="<?= BASE_URL ?>/dosen">Data Dosen</a>
    </li>
</ul>
