<?php

namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function preview() {
        return Inertia::render('Admin/Preview');
    }
}
