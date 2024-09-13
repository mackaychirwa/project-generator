<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CreateProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function showForm()
    {
        return view('project.create');
    }

    public function create(Request $request)
    {
       $data = new CreateProjectService();
       return $data->create($request);
    }

}
