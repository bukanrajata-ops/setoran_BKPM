<?php
// Acara 13: $errors (pesan error per field) dikirim dari MahasiswaController
// setelah divalidasi oleh MahasiswaService.
$errors = $errors ?? [];
?>
<div class="card shadow-sm">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Edit Mahasiswa</h4>
    </div>
    <div class="card-body">
        <form method="post" action="<?= BASE_URL ?>/mahasiswa/<?= urlencode($originalNim ?? $mahasiswa['nim'] ?? '') ?>/update" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">NIM</label>
                    <input name="nim" class="form-control <?= isset($errors['nim']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($mahasiswa['nim'] ?? '') ?>">
                    <?php if (isset($errors['nim'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['nim']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input name="nama" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($mahasiswa['nama'] ?? '') ?>">
                    <?php if (isset($errors['nama'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['nama']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($mahasiswa['email'] ?? '') ?>">
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Prodi</label>
                    <select name="prodi_id" class="form-select <?= isset($errors['prodi_id']) ? 'is-invalid' : '' ?>">
                        <option value="">- pilih -</option>
                        <?php foreach ($prodiList as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= ((int) ($mahasiswa['prodi_id'] ?? 0) === (int) $p['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['prodi_id'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['prodi_id']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Angkatan</label>
                    <input type="number" name="angkatan" class="form-control <?= isset($errors['angkatan']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars((string) ($mahasiswa['angkatan'] ?? '')) ?>">
                    <?php if (isset($errors['angkatan'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['angkatan']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>">
                        <option value="aktif" <?= ($mahasiswa['status'] ?? '') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="cuti" <?= ($mahasiswa['status'] ?? '') === 'cuti' ? 'selected' : '' ?>>Cuti</option>
                        <option value="lulus" <?= ($mahasiswa['status'] ?? '') === 'lulus' ? 'selected' : '' ?>>Lulus</option>
                    </select>
                    <?php if (isset($errors['status'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['status']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-warning">Simpan Perubahan</button>
                <a href="<?= BASE_URL ?>/mahasiswa" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
