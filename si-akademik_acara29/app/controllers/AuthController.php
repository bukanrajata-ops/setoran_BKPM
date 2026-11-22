<?php
// Controller pertama yang bukan CRUD standar: hanya aksi register().
// Acara 20: ditambah aksi login().
class AuthController {
    private User $model;

    public function __construct() {
        $this->model = new User();
    }

    public function register(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        $errors = [];
        foreach (['username', 'email', 'password'] as $field) {
            if (!is_array($data) || empty($data[$field]) || !is_string($data[$field])) {
                $errors[$field] = "Field $field wajib diisi";
            }
        }
        if ($errors) {
            Response::json(400, false, 'Username, email, dan password wajib diisi', null, $errors);
        }

        // Kata sandi tidak pernah disimpan sebagai teks biasa
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        try {
            $id = $this->model->create($data);
        } catch (InvalidArgumentException $e) {
            Response::json(422, false, $e->getMessage(), null, ['email' => $e->getMessage()]);
        } catch (DomainException $e) {
            $field = str_contains($e->getMessage(), 'Email') ? 'email' : 'username';
            Response::json(409, false, $e->getMessage(), null, [$field => $e->getMessage()]);
        }

        // Hash password tidak ikut dikembalikan ke klien
        Response::json(201, true, 'Registrasi berhasil', ['id' => $id]);
    }

    public function login(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        $errors = [];
        foreach (['username', 'password'] as $field) {
            if (!is_array($data) || empty($data[$field]) || !is_string($data[$field])) {
                $errors[$field] = "Field $field wajib diisi";
            }
        }
        if ($errors) {
            Response::json(400, false, 'Username dan password wajib diisi', null, $errors);
        }

        $user = $this->model->findByUsername($data['username']);
        // Pesan sama untuk username salah maupun password salah (tidak membocorkan akun mana yang ada)
        if (!$user || !password_verify($data['password'], $user['password'])) {
            Response::json(401, false, 'Username atau password salah');
        }

        $token = Auth::generateToken(['id' => $user['id'], 'role' => $user['role']]);
        Response::json(200, true, 'Login berhasil', ['token' => $token, 'role' => $user['role']]);
    }
}
