<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function __construct()
    {
        helper('form');
    }
    public function index()
    {
        return view('dashboard/index');
    }

    public function task()
    {
        return view('dashboard/task/index');
    }
}
