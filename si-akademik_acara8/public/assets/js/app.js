// public/assets/js/app.js
// JavaScript untuk SI Akademik (Acara 8)

document.addEventListener('DOMContentLoaded', function () {

    // 1. Konfirmasi sebelum hapus.
    // Form yang punya atribut data-confirm akan menampilkan dialog konfirmasi.
    // Contoh: <form data-confirm="Hapus data ini?"> ... </form>
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm(form.dataset.confirm)) {
                e.preventDefault(); // batalkan submit jika user klik Cancel
            }
        });
    });

    // 2. Flash message hilang otomatis setelah 4 detik.
    var alertEl = document.querySelector('.alert-dismissible');
    if (alertEl && window.bootstrap) {
        setTimeout(function () {
            bootstrap.Alert.getOrCreateInstance(alertEl).close();
        }, 4000);
    }
});
