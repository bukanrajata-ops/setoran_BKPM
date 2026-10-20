<?php
// Acara 13: $errors (pesan error per field) dan $old (isian sebelumnya)
// dikirim dari MahasiswaController setelah divalidasi oleh MahasiswaService.
$errors = $errors ?? [];
$old = $old ?? [];
?>
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Tambah Mahasiswa</h4>
    </div>
    <div class="card-body">
        <form method="post" action="<?= BASE_URL ?>/mahasiswa" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">NIM</label>
                    <input name="nim" class="form-control <?= isset($errors['nim']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($old['nim'] ?? '') ?>">
                    <?php if (isset($errors['nim'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['nim']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input name="nama" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($old['nama'] ?? '') ?>">
                    <?php if (isset($errors['nama'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['nama']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Prodi</label>
                    <select name="prodi_id" class="form-select <?= isset($errors['prodi_id']) ? 'is-invalid' : '' ?>">
                        <option value="">- pilih -</option>
                        <?php foreach ($prodiList as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= ((int) ($old['prodi_id'] ?? 0) === (int) $p['id']) ? 'selected' : '' ?>>
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
                           value="<?= htmlspecialchars($old['angkatan'] ?? date('Y')) ?>">
                    <?php if (isset($errors['angkatan'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['angkatan']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <?php $statusOld = $old['status'] ?? 'aktif'; ?>
                    <select name="status" class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>">
                        <option value="aktif" <?= $statusOld === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="cuti" <?= $statusOld === 'cuti' ? 'selected' : '' ?>>Cuti</option>
                        <option value="lulus" <?= $statusOld === 'lulus' ? 'selected' : '' ?>>Lulus</option>
                    </select>
                    <?php if (isset($errors['status'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['status']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary">Simpan</button>
                <a href="<?= BASE_URL ?>/mahasiswa" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
