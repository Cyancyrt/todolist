<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class NoteController extends BaseController
{
    public function index()
    {
        return view('dashboard/notes/index');
    }
}
