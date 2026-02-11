<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        return match ($role) {
            'admin'   => redirect('/admin/reset'),
            'teacher' => redirect('/teacher/attendance'),
            'parent'  => redirect('/parent/dashboard'),
            'student' => redirect('/student/dashboard'),
            default   => redirect('/login'),
        };
    }
}
