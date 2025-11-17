<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DemoWorker extends BaseCommand
{
    protected $group = 'demo';
    protected $name = 'worker:listen'; // Changed name for clarity
    protected $description = 'Worker that runs continuously, checking for new tasks.';
    protected $usage = 'worker:listen';

    public function run(array $params)
    {
        CLI::write(CLI::color('*** Worker Listening Started ***', 'green'));
        CLI::write('Press Ctrl+C to stop the worker.', 'light_gray');
        CLI::newLine();

        // The main continuous loop
        while (true) {
            
            // 1. Log the check time
            CLI::write("Checking for new messages at: " . date('Y-m-d H:i:s'), 'white');

            // --- 2. THE CORE LOGIC: Check for Messages ---
            $this->checkForNewMessages();
            // ---------------------------------------------
            
            // 3. Pause the worker for a specified duration
            // This prevents the script from consuming 100% CPU
            $sleepSeconds = 5; 
            CLI::write("Sleeping for {$sleepSeconds} seconds...", 'cyan');
            sleep($sleepSeconds);
            
            // Optional: Clear the terminal every few loops for a cleaner view
            // if ($counter++ % 10 === 0) {
            //     CLI::clear(); 
            // }
        }

        // This line is technically unreachable but is good practice
        CLI::write(CLI::color('*** Worker Stopped ***', 'red'));
    }

    /**
     * Placeholder method for your actual message retrieval and processing logic.
     */
    protected function checkForNewMessages()
    {
        // Example: In a real application, you would check:
        
        // 1. A database table for records where 'status' is 'pending'.
        // $jobs = model('JobModel')->where('status', 'pending')->findAll();
        
        // 2. An external queue service (like Redis, RabbitMQ, or Amazon SQS).
        // $message = $queueService->getNewMessage();

        // For this demo, we'll just print a dummy message sometimes
        if (rand(1, 5) === 1) {
            $message = 'NEW MESSAGE FOUND! Processing job: ' . uniqid();
            CLI::write(CLI::color($message, 'yellow'));
            // Simulate work being done
            // sleep(2); 
            // Update the job status in the DB/Queue to 'completed'
        } else {
            CLI::write('No new messages found.', 'light_gray');
        }
    }
}