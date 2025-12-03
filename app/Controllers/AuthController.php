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
        helper(['url', 'form', 'session', 'cookie']);
        $this->userModel = new UsersModel();
        $this->validation = \Config\Services::validation();
        $this->session = session();
    }
    public function index(): string
    {
        $savedEmail = get_cookie('remember_email');
        return view('auth/login.php', ['session' => session(), 'savedEmail' => $savedEmail]);
    }
    public function auth()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember'); // Ambil value checkbox

        $user = $this->userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $sessionData = [
                'user_id'    => $user['id'],
                'name'       => $user['name'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ];

            session()->set($sessionData);

            // --- LOGIKA REMEMBER ME ---
            if ($remember) {
                set_cookie('remember_email', $email, 3600 * 24 * 30);
            } else {
                delete_cookie('remember_email');
            }

            return redirect()->to(base_url('/dashboard'));
        } else {
            session()->setFlashdata('error', 'Username atau password salah.');
            return redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        session()->destroy(); // Menghapus semua data sesi
         // simpan notifikasi 1x tampil
        return redirect()->to('/?logout=1'); // Arahkan kembali ke halaman login
    }

    public function register()
    {
        if ($this->request->getMethod() == 'POST') {
            $rules = [
                'name' => [
                    'rules' => 'required|min_length[3]|max_length[100]',
                    'errors' => [
                        'required' => 'Nama harus diisi.',
                        'min_length' => 'Nama terlalu pendek.',
                        'max_length' => 'Nama terlalu panjang.'
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
                    // Minimal 8 karakter, harus ada Huruf Besar, Kecil, Angka, dan Simbol
                    'rules' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/]',
                    'errors' => [
                        'required' => 'Password harus diisi.',
                        'min_length' => 'Password minimal 8 karakter.',
                        'regex_match' => 'Password harus mengandung setidaknya 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 simbol (spesial karakter).'
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

            // Sanitasi dan Persiapan Data
            $data = [
                // Gunakan htmlspecialchars untuk mencegah XSS pada nama
                'name'       => htmlspecialchars(trim($this->request->getPost('name'))),
                'email'      => trim($this->request->getPost('email')),
                'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'       => 'personal',
                'created_at' => date('Y-m-d H:i:s'),
            ];

            try {
                $success = $this->userModel->save($data);
                
                if ($success) {
                    $this->session->setFlashdata('success', 'Registrasi berhasil, silakan login.');
                    return redirect()->to('/login');
                } else {
                    $this->session->setFlashdata('error', 'Gagal menyimpan data ke database.');
                    return redirect()->back()->withInput();
                }
            } catch (\Exception $e) {
                // Tangkap error jika ada masalah database
                $this->session->setFlashdata('error', 'Terjadi kesalahan sistem.');
                return redirect()->back()->withInput();
            }
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
                $this->session->setFlashdata('success', 'Data berhasil diperbarui.');
            } else {
                $this->session->setFlashdata('error', 'Data gagal diperbarui.');
            }   
        } catch (\Throwable $th) {
            dd($th);
            $this->session->setFlashdata('error', 'Terjadi kesalahan pada server: ' . $th->getMessage());
        }
        return redirect()->to('/dashboard');
    }
}
