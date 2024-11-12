<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FilterController extends Controller
{
    public function index() {
        return Inertia::render('Admin/Filters');
    }

    public function getAll() {
        return Filter::query()->get(['id', 'name', 'icon']);
    }
}
