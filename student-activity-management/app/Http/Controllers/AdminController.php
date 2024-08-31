<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
    public function showDashboard()
    {
        $months = ['January', 'February', 'March', 'April', 'May']; // Thay thế bằng dữ liệu thực tế
        $registrations = [10, 20, 15, 30, 25]; // Thay thế bằng dữ liệu thực tế

        return view('admin.dashboard', compact('months', 'registrations'));
    }
}
