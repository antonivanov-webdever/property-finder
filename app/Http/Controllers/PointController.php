<?php

namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PointController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Points');
    }

    public function create()
    {
        return Inertia::render('Admin/CreatePoint');
    }

    public function edit(Point $point)
    {
        dd($point);
        return Inertia::render('Admin/EditPoint', ['id' => $point->id]);
    }

    public function store(Point $point)
    {
        dd($point);
    }

    public function update(Point $point, string $id): string
    {
        dd($point);
    }

    public function destroy(Point $point): void
    {
        dd('destroy', $point);
    }
}
