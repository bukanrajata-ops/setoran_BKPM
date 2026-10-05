<?php
// routes/web.php
// Acara 5: routing berbasis path URL (bukan ?url= lagi), mendukung
// placeholder seperti {nim} untuk parameter dinamis.

return [
    'GET' => [
        '/'                 => ['HomeController', 'index'],
        '/mahasiswa'        => ['MahasiswaController', 'index'],
        '/mahasiswa/create' => ['MahasiswaController', 'create'],
        '/mahasiswa/{nim}'  => ['MahasiswaController', 'show'],
        '/dosen'            => ['DosenController', 'index'],
    ],
];
