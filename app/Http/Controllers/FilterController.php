<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FilterController extends Controller
{
    public function index() {
        $filters = Filter::getFormattedFilters();
        return Inertia::render('Admin/Filters', [
            'filters' => $filters
        ]);
    }

    public function create() {
        return Inertia::render('Admin/CreateFilter');
    }

    public function edit(Filter $filter) {
        return Inertia::render('Admin/EditFilter', [
            'filter' => $filter
        ]);
    }

    public function store(Request $request): RedirectResponse {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $imagePath = $request
            ->file('icon')
            ->storeAs(
                'storage/images',
                $request->file('icon')->getClientOriginalName(),
                'admin'
            );

        Filter::create([
            'name' => $request->get('name'),
            'icon' => '/admin/' . $imagePath,
        ]);

        return redirect()->route('filters.index')->with('message', 'Точка успешно создана.');
    }

    public function update(Filter $filter, Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
        ]);

        if ($request->hasFile('icon')) {
            $request->validate([
                'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            ]);
            $imagePath = $request
                ->file('icon')
                ->storeAs(
                    'storage/images',
                    $request->file('icon')->getClientOriginalName(),
                    'admin'
                );
            $imagePath = '/admin/' . $imagePath;
        } else {
            $imagePath = $filter->icon;
        }

        $filter->name = $request->get('name');
        $filter->icon = $imagePath;

        $filter->save();

        return redirect()->route('filters.index')->with('message', "Фильтр №{$filter->id} успешно обновлен.");
    }

    public function destroy(Filter $filter) {
        $filter->delete();

        return redirect()->route('filters.index')->with('message', "Фильтр №{$filter->id} успешно удален.");
    }

    public function getAll() {
        return Filter::query()->orderBy('name')->get(['id', 'name', 'icon']);
    }
}
