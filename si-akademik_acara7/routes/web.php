<?php

return [
    'GET' => [

        '/login' => [
            'controller' => 'AuthController',
            'action'     => 'loginForm',
        ],

        '/logout' => [
            'controller' => 'AuthController',
            'action'     => 'logout',
        ],

        '/' => [
            'controller' => 'HomeController',
            'action'     => 'index',
            'middleware' => ['AuthMiddleware'],
        ],

        '/mahasiswa' => [
            'controller' => 'MahasiswaController',
            'action'     => 'index',
            'middleware' => ['AuthMiddleware'],
        ],

        '/mahasiswa/create' => [
            'controller' => 'MahasiswaController',
            'action'     => 'create',
            'middleware' => ['AuthMiddleware'],
        ],

        '/mahasiswa/{nim}' => [
            'controller' => 'MahasiswaController',
            'action'     => 'show',
            'middleware' => ['AuthMiddleware'],
        ],

        '/dosen' => [
            'controller' => 'DosenController',
            'action'     => 'index',
            'middleware' => ['AuthMiddleware'],
        ],

    ],

    'POST' => [

        '/login' => [
            'controller' => 'AuthController',
            'action'     => 'login',
        ],

    ],
];