<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DemoRequest;

class DemoRequestController extends Controller
{
    //
    public function index()
    {
        $demoRequests = DemoRequest::latest()->get();

        return view('main_admin.demoRequests.index', compact('demoRequests'));
    }
}
