<?php
// app/Views/layouts/main.php
// $content dan $active dikirim dari public/index.php sebelum file ini di-require
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/navbar.php';
require __DIR__ . '/../partials/flash.php';
require $content;
require __DIR__ . '/../partials/footer.php';
