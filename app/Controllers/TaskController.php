<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TasksModel;
use CodeIgniter\HTTP\ResponseInterface;

class TaskController extends BaseController
{
    protected $taskModel, $user;

    public function __construct(){
        helper(['form']);
        $this->user = session()->get('user');
        $this->taskModel = new TasksModel();
    }
    public function index()
    {
        return view('dashboard/task/index');
    }
    public function create()
    {
        return view('dashboard/task/create');
    }
    public function store(){
        $rules = [
            'title'       => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[1000]',
            'due_time'    => 'required|valid_date[Y-m-d H:i]', // sesuaikan format flatpickr
            'priority'    => 'required|in_list[low,medium,high]',
            'recurring'    => 'permit_empty|in_list[none,daily,weekly,monthly,yearly]',
        ];
    }
}
