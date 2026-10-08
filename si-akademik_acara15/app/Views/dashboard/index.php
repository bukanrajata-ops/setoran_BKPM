<div class="container-dashboard">
    <h2 class="fw-bold mb-3">Sistem Informasi Akademik</h2>

    <div class="alert alert-success py-2">
        Selamat datang, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'admin') ?></strong>.
    </div>

    <div class="menu-title">Menu Utama</div>

    <div class="row g-3 mt-0">
        <div class="col-md-6">
            <div class="menu-card">
                <h5>Data Mahasiswa</h5>
                <p>Kelola data mahasiswa.</p>
                <a href="<?= BASE_URL ?>/mahasiswa" class="btn btn-primary btn-sm w-100">Buka Mahasiswa</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="menu-card">
                <h5>Data Dosen</h5>
                <p>Kelola data dosen.</p>
                <a href="<?= BASE_URL ?>/dosen" class="btn btn-success btn-sm w-100">Buka Dosen</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="menu-card">
                <h5>Data Prodi</h5>
                <p>Kelola data program studi.</p>
                <a href="<?= BASE_URL ?>/prodi" class="btn btn-success btn-sm w-100">Buka Prodi</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="menu-card">
                <h5>Data Mata Kuliah</h5>
                <p>Kelola data mata kuliah.</p>
                <a href="<?= BASE_URL ?>/matakuliah" class="btn btn-dark btn-sm w-100">Buka Mata Kuliah</a>
            </div>
        </div>
    </div>
</div>

<style>
.container-dashboard {
    max-width: 1000px;
    margin: 0 auto;
}
.menu-title {
    background: #0d6efd;
    color: #fff;
    font-weight: 600;
    padding: 6px 10px;
    border-radius: 3px 3px 0 0;
    margin-bottom: 8px;
}
.menu-card {
    border: 1px solid #e1e1e1;
    border-radius: 4px;
    padding: 18px 12px 12px;
    text-align: center;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
}
.menu-card h5 {
    font-size: 15px;
    margin-bottom: 5px;
}
.menu-card p {
    font-size: 12px;
    color: #777;
    margin-bottom: 12px;
}
</style>
