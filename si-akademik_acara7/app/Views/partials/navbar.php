<!-- app/Views/partials/navbar.php -->
<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <ul class="nav nav-pills mb-0">
        <li class="nav-item">
            <a class="nav-link <?= ($active ?? '') === 'mahasiswa' ? 'active' : '' ?>" href="<?= BASE_URL ?>/mahasiswa">Data Mahasiswa</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($active ?? '') === 'dosen' ? 'active' : '' ?>" href="<?= BASE_URL ?>/dosen">Data Dosen</a>
        </li>
    </ul>
    <?php if (!empty($_SESSION['logged_in'])): ?>
    <div class="d-flex align-items-center gap-2">
        <span class="text-muted small">Halo, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></strong></span>
        <a href="<?= BASE_URL ?>/logout" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>
    <?php endif; ?>
</div>
