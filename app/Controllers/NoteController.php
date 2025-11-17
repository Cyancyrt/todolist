<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class NoteController extends BaseController
{
    protected $notesModel, $activitiesModel;

    public function __construct()
    {
        helper(['form', 'security']);
        $this->notesModel = new \App\Models\NotesModel();
        $this->activitiesModel = new \App\Models\ActivitiesModel();
    }
    public function index()
    {
        $notes = $this->notesModel->getUserNotes(session()->get('user_id'));
        return view('dashboard/notes/index', ['notes' => $notes]);
    }
    public function detail($id)
    {
        return view('dashboard/notes/detail', ['noteId' => $id]);
    }
    public function create()
    {
        $activities = $this->activitiesModel->where('created_by', session()->get('user_id'))->findAll();
        return view('dashboard/notes/create', ['activities' => $activities]);
    }
    public function edit($id)
    {
        $task = $this->notesModel->find($id);
        $activities = $this->activitiesModel->where('created_by', session()->get('user_id'))->findAll();
        return view('dashboard/notes/edit', ['noteId' => $id, 'task' => $task, 'activities' => $activities]);
    }
    public function save()
    {
         // --- 1. Aturan Validasi ---
        $validationRules = [
            'activity_id' => [
                'rules' => 'permit_empty|integer',
                'errors' => [
                    'integer' => 'Activity ID harus berupa angka.'
                ]
            ],
            'title' => [
                'rules' => 'required|min_length[3]|max_length[150]',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong.',
                    'min_length' => 'Judul minimal 3 karakter.',
                    'max_length' => 'Judul maksimal 150 karakter.'
                ]
            ],
            'content' => [
                'rules' => 'permit_empty|string',
            ],
        ];

        // --- 2. Jalankan Validasi ---
        if (! $this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // --- 3. Ambil data aman dari input ---
        $data = [
            'user_id'     => session()->get('user_id'), // ambil ID user dari session
            'activity_id' => $this->request->getPost('activity_id') ?: null,
            'title'       => esc($this->request->getPost('title')),
            'content'     => esc($this->request->getPost('content')),
            'created_at'  => date('Y-m-d H:i:s'),
        ];
        try {
            // --- 4. Simpan data ke database ---
            $this->notesModel->insert($data);

            return redirect()->back()
                ->with('success', 'Catatan berhasil disimpan.');
        } catch (\Exception $e) {
            dd($e);
            // --- 5. Tangani error ---
            log_message('error', 'Gagal menyimpan note: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan catatan.');
        }
    }
    public function update($id)
    {
        // --- 1. Pastikan data dengan ID tersebut ada ---
        $note = $this->notesModel->find($id);
        if (!$note) {
            return redirect()->back()
                ->with('error', 'Catatan tidak ditemukan.');
        }

        // --- 2. Aturan Validasi Aman ---
        $validationRules = [
            'activity_id' => [
                'rules' => 'permit_empty|integer',
                'errors' => [
                    'integer' => 'Activity ID harus berupa angka.'
                ]
            ],
            'title' => [
                'rules' => 'required|min_length[3]|max_length[150]',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong.',
                    'min_length' => 'Judul minimal 3 karakter.',
                    'max_length' => 'Judul maksimal 150 karakter.'
                ]
            ],
            'content' => [
                'rules' => 'permit_empty|string',
            ],
        ];

        // --- 3. Jalankan Validasi ---
        if (! $this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // --- 4. Ambil input aman dari request ---
        $data = [
            'user_id'     => session()->get('user_id'),
            'activity_id' => $this->request->getPost('activity_id') ?: null,
            'title'       => esc($this->request->getPost('title')),
            'content'     => esc($this->request->getPost('content')),
        ];

        try {
            // --- 5. Update data menggunakan Model ---
            $this->notesModel->update($id, $data);

            return redirect()->back()
                ->with('success', 'Catatan berhasil diperbarui.');
        } catch (\Exception $e) {
            // --- 6. Tangani dan log error ---
            log_message('error', 'Gagal memperbarui note ID ' . $id . ': ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui catatan.');
        }
    }
    public function delete($id)
    {

        $note = $this->notesModel->find($id);
        if (!$note) {
            return redirect()->back()->with('error', 'Catatan tidak ditemukan.');
        }

        try {
            $this->notesModel->delete($id);
            return redirect()->back()->with('success', 'Catatan berhasil dihapus.');
        } catch (\Exception $e) {
            log_message('error', 'Gagal menghapus note ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus catatan.');
        }
    }
}
