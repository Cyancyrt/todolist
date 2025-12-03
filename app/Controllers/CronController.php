<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Workers\ScheduledPushService;
use App\Workers\ScheduledPushWorker;
use CodeIgniter\HTTP\ResponseInterface;

class CronController extends BaseController
{
    public function runPush()
    {
        // Optional: protect with token
        $token = $this->request->getGet('token');
        if ($token !== getenv('CRON_TOKEN')) {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }

        // Jalankan worker
        $worker = new ScheduledPushService();
        $worker->run();

        return "return 'Scheduler executed';";
    }
}
