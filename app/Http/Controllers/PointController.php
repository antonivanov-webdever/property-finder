<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\Point;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PointController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Points', [
            'points' => Point::orderBy('created_at', 'desc')
                ->paginate(15)
                ->through(fn ($point) => [
                    'id' => $point->id,
                    'image' => $point->image,
                    'name' => $point->name,
                    'address' => $point->address,
                    'description' => $point->description,
                    'tg_link' => $point->tg_link,
                    'youtube_link' => $point->youtube_link,
                    'filter' => Filter::find($point->filter_id)->name,
                    'coordinates' => $point->coordinates,
                    'is_visible' => $point->is_visible,
                    'updated_at' => $point->updated_at->toDateTimeString(),
                ])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/CreatePoint');
    }

    public function edit(Point $point)
    {
        return Inertia::render('Admin/EditPoint', ['point' => $point]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|min:3|max:255',
            'tg_link' => 'nullable|min:3|max:255|url:https,t.me',
            'youtube_link' => 'nullable|min:3|max:255|url:https,youtu.be',
            'filter_id' => 'required|integer|exists:filters,id',
            'coordinates' => 'required|array|min:2|max:2',
            'address' => 'required|min:3|max:255',
        ]);

        $imagePath = $request->file('image')->store('storage/images', 'admin');
        $coordinates = Json::encode($request->get('coordinates'));

        Point::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'image' => '/admin/' . $imagePath,
            'tg_link' => $request->get('tg_link'),
            'youtube_link' => $request->get('youtube_link'),
            'filter_id' => $request->get('filter_id'),
            'coordinates' => $coordinates,
            'address' => $request->get('address'),
        ]);

        return redirect()->route('points.index')->with('message', 'Точка успешно создана.');
    }

    public function save(Request $request): RedirectResponse
    {
        $points = $request->all();

        foreach ($points as $point) {
            $dbPoint = Point::find($point['id']);
            $dbPoint->is_visible = $point['is_visible'];
            $dbPoint->save();
        }

        return redirect()->route('points.index')->with('message', 'Сохранение прошло успешно.');
    }

    public function update(Point $point, Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'required|min:3|max:255',
            'tg_link' => 'nullable|min:3|max:255|url:https,t.me',
            'youtube_link' => 'nullable|min:3|max:255|url:https,youtu.be',
            'filter_id' => 'required|integer|exists:filters,id',
            'coordinates' => 'required|array|min:2|max:2',
            'address' => 'required|min:3|max:255',
        ]);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $imagePath = $request->file('image')->store('storage/images', 'admin');
            $imagePath = '/admin/' . $imagePath;
        } else {
            $imagePath = $point->image;
        }

        $coordinates = Json::encode($request->get('coordinates'));

        $point->name = $request->get('name');
        $point->description = $request->get('description');
        $point->image = $imagePath;
        $point->tg_link = $request->get('tg_link');
        $point->youtube_link = $request->get('youtube_link');
        $point->filter_id = $request->get('filter_id');
        $point->coordinates = $coordinates;
        $point->address = $request->get('address');

        $point->save();

        return redirect()->route('points.index')->with('message', "Точка №{$point->id} успешно обновлена.");
    }

    public function destroy(Point $point): RedirectResponse
    {
        $point->delete();

        return redirect()->route('points.index')->with('message', "Точка №{$point->id} успешно удалена.");
    }

    public function getPointsOMJson(): string
    {
        return Point::getAllPointsJsonForOM();
    }
}
