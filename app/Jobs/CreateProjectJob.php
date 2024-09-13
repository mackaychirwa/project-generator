<?php

namespace App\Jobs;

use App\Services\CreateProjectService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateProjectJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $name;
    protected $directory;
    protected $backend;
    protected $frontend;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name, $directory, $backend, $frontend)
    {
        $this->name = $name;
        $this->directory = $directory;
        $this->backend = $backend;
        $this->frontend = $frontend;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        // Backend commands
        $backendCommands = [
            'laravel' => "composer create-project --prefer-dist laravel/laravel {$this->name}-server",
            'django' => "django-admin startproject {$this->name}",
            'nodejs' => "npx express-generator {$this->name}"
        ];

        // Frontend commands
        $frontendCommands = [
            'react' => "npm create vite@latest my-project -- --template {$this->name}",
            'vue' => "vue create {$this->name}",
            'angular' => "ng new {$this->name}"
        ];

        if (!array_key_exists($this->backend, $backendCommands) || !array_key_exists($this->frontend, $frontendCommands)) {
            throw new \Exception('Unsupported backend or frontend framework');
        }

        // Create backend project
        // $backendCommand = $backendCommands[$this->backend];
        // $this->executeCommand($this->directory, $backendCommand);

        // Create frontend project in the same directory or a subfolder
        $frontendCommand = $frontendCommands[$this->frontend];
        $frontendDirectory = "{$this->directory}_frontend";
        $this->executeCommand($this->directory, $frontendCommand);

        // $this->executeCommand($frontendDirectory, $frontendCommand);
    }

    private function executeCommand($directory, $command)
    {
        $fullCommand = "cd " . escapeshellarg($directory) . " && " . $command;
        $output = shell_exec($fullCommand);
        if ($output === null) {
            throw new \Exception('Command failed to execute');
        }
    }
}
