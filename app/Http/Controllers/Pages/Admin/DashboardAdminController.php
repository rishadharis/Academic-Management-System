<?php

namespace App\Http\Controllers\Pages\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        return view("pages.admin.dashboard");
    }
}
