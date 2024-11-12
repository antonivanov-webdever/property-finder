<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\Point;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PointController extends Controller
{
    public function index()
    {
        $points = Point::orderBy('created_at', 'desc')->get();
        $formatted_points = $this->formattedForFrontend($points);

        return Inertia::render('Admin/Points', [
            'points' => $formatted_points
        ]);
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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'required|min:3|max:255',
            'tgLink' => 'nullable|min:3|max:255|url:https,t.me',
            'youtubeLink' => 'nullable|min:3|max:255|url:https,youtu.be',
            'filter' => 'required|integer|exists:filters,id',
            'coordinates' => 'required|array',
            'address' => 'required|min:3|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('image')->store('images', 'public');
        $coordinates = Json::encode($request->get('coordinates'));

        $point = new Point;
        $point->name = $request->get('name');
        $point->image = $imagePath;
        $point->description = $request->get('description');
        $point->tg_link = $request->get('tgLink');
        $point->youtube_link = $request->get('youtubeLink');
        $point->filter_id = $request->get('filter');
        $point->coordinates = $coordinates;
        $point->address = $request->get('address');
        $point->save();

        return redirect()->route('points.index')->with('success', 'Point created successfully.');
    }

    public function update(Point $point, string $id): string
    {
        dd($point);
    }

    public function destroy(Point $point): void
    {
        dd('destroy', $point);
    }

    private function formattedForFrontend(Collection $points): array {
        $formatted_points = [];

        foreach ($points as $point) {
            $formatted_points[] = [
                'id' => $point->id,
                'name' => $point->name,
                'description' => $point->description,
                'tgLink' => $point->tg_link,
                'youtubeLink' => $point->youtube_link,
                'filter' => Filter::find($point->filter_id)->name,
                'coordinates' => $point->coordinates,
                'address' => $point->address,
                'image' => $point->image,
                'isVisible' => $point->is_visible,
                'updatedAt' => $point->updated_at->toDateTimeString(),
            ];
        }

        return $formatted_points;
    }
}
