<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class CalendarController extends BaseController
{
    public function index()
    {
        return view('dashboard/calendar/index');
    }
}
