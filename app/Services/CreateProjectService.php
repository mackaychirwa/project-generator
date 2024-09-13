<?php

namespace App\Services;

use App\Jobs\CreateProjectJob;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CreateProjectService
{
    public function create($request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'directory' => 'required|string',
            'backend' => 'required|in:laravel,django,nodejs',
            'frontend' => 'required|in:react,vue,angular',
        ]);

        $name = $validated['name'];
        $directory = $validated['directory'];
        $backend = $validated['backend'];
        $frontend = $validated['frontend'];

        try {
            CreateProjectJob::dispatch($name, $directory, $backend, $frontend);
            return redirect()->back()->with('status', 'Project created successfully!');
        } catch (ProcessFailedException $e) {
            return redirect()->back()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    private function createProject($name, $directory, $backend, $frontend)
    {
        // Backend commands
        $backendCommands = [
            'laravel' => "composer create-project --prefer-dist laravel/laravel $name",
            'django' => "django-admin startproject $name",
            'nodejs' => "npx express-generator $name"
        ];

        // Frontend commands
        $frontendCommands = [
            'react' => "npm create vite@latest my-project -- --template $name",
            'vue' => "vue create $name",
            'angular' => "ng new $name"
        ];

        if (!array_key_exists($backend, $backendCommands) || !array_key_exists($frontend, $frontendCommands)) {
            throw new \Exception('Unsupported backend or frontend framework');
        }

        // Create backend project
        $backendCommand = $backendCommands[$backend];
        $this->executeCommand($directory, $backendCommand);

        // Create frontend project in the same directory or a subfolder
        $frontendCommand = $frontendCommands[$frontend];
        $frontendDirectory = "$directory/$name-frontend";
        $this->executeCommand($frontendDirectory, $frontendCommand);
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
