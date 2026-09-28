<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::getFormattedCategories();
        return Inertia::render('Admin/Categories', [
            'categories' => $categories
        ]);
    }

    public function create() {
        return Inertia::render('Admin/CreateCategory');
    }

    public function edit(Category $category) {
        return Inertia::render('Admin/EditCategory', [
            'category' => $category
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

        Category::create([
            'name' => $request->get('name'),
            'icon' => '/admin/' . $imagePath,
        ]);

        return redirect()->route('categories.index')->with('message', 'Категория успешно создана.');
    }

    public function update(Category $category, Request $request): RedirectResponse
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
            $imagePath = $category->icon;
        }

        $category->name = $request->get('name');
        $category->icon = $imagePath;

        $category->save();

        return redirect()->route('categories.index')->with('message', "Категория №{$category->id} успешно обновлена.");
    }

    public function destroy(Category $category) {
        $category->delete();

        return redirect()->route('categories.index')->with('message', "Категория №{$category->id} успешно удалена.");
    }

    public function getAll() {
        return Category::query()->orderBy('name')->get(['id', 'name', 'icon']);
    }
}
