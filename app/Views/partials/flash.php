<?php
// app/Views/partials/flash.php
// Tugas Mandiri: tampilkan flash message sekali saja, lalu hapus dari session.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?= htmlspecialchars($_SESSION['flash_message']['type']) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['flash_message']['text']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>
