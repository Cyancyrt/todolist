<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SummaryController extends BaseController
{
    public function index()
    {
        return view('dashboard/summary/index');
    }
}
