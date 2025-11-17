<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $userModel, $validation, $session;
    /**
     * Class constructor.
     */
    public function __construct()
    {
        helper(['url', 'form', 'session']);
        $this->userModel = new UsersModel();
        $this->session = session();
        $this->validation = \Config\Services::validation();
    }
    public function index(): string
    {
        return view('auth/login.php', ['session' => $this->session]);
    }
    public function auth()
    {
        // Mengambil data 'username' dan 'password' dari form
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Minta UserModel untuk memeriksa kredensial di kolom 'email'
        // --- INILAH PERUBAHANNYA ---
        $user = $this->userModel->where('email', $email)->first();
        // Verifikasi user dan password
        if ($user && password_verify($password, $user['password'])) {
            // Jika login berhasil, siapkan data sesi
            $sessionData = [
                'user_id'    => $user['id'],
                'name'       => $user['name'], // menyesuaikan dengan field 'name' di tabel users
                'email'      => $user['email'], // menggantikan 'email'
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ];

            session()->set($sessionData);

            return redirect()->to(base_url('/dashboard'));
        } else {
            // Jika login gagal
            session()->setFlashdata('error', 'Username atau password salah.');
            return redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        session()->destroy(); // Menghapus semua data sesi
        return redirect()->to('/'); // Arahkan kembali ke halaman login
    }

        public function register()
    {
        if ($this->request->getMethod() == 'POST') {
            $rules = [
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama harus diisi.'
                    ],
                ],
                'email' => [
                    'rules' => 'required|valid_email|is_unique[users.email]',
                    'errors' => [
                        'required' => 'Email harus diisi.',
                        'valid_email' => 'Format email tidak valid.',
                        'is_unique' => 'Email sudah digunakan.'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[6]',
                    'errors' => [
                        'required' => 'Password harus diisi.',
                        'min_length' => 'Password minimal 6 karakter.'
                    ]
                ],
                'confirmPassword' => [
                    'rules' => 'required|matches[password]',
                    'errors' => [
                        'required' => 'Konfirmasi password harus diisi.',
                        'matches' => 'Konfirmasi password tidak cocok dengan password.'
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                return view('auth/register', [
                    'validation' => $this->validator
                ]);
            }

            $data = [
                'name'       => $this->request->getPost('name'),
                'email'      => $this->request->getPost('email'),
                'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'       => 'personal',
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $success = $this->userModel->save($data);
            if ($success) {
                $this->session->setFlashdata('success', 'Registrasi berhasil, silakan login.');
            } else {
                $this->session->setFlashdata('error', 'Registrasi gagal, silakan coba lagi.');
            }
            return redirect()->to('/'); // Ganti dengan file login Anda view('auth/login.php');
        }
        return view('auth/register.php');
    }
    public function editProfile($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }
        return view('dashboard/profile/edit', ['user' => $user]);
    }
    public function updateProfile($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $rules = [
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama harus diisi.'
                ],
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi.',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
            ];
            $success = $this->userModel->update($id, $data);
            if ($success) {
                $this->session->getFlashdata('success', 'Data berhasil diperbarui.');
            } else {
                $this->session->getFlashdata('error', 'Data gagal diperbarui.');
            }
        } catch (\Throwable $th) {
            dd($th);
            $this->session->getFlashdata('error', 'Terjadi kesalahan pada server: ' . $th->getMessage());
        }
        return redirect()->to('/dashboard');
    }
}
