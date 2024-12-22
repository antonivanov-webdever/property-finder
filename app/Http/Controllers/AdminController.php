<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\Point;

class AdminController extends Controller
{
    public function preview() {
        return Inertia::render('Admin/Preview');
    }

    public function uploadPointsListCsv(Request $request): RedirectResponse {
        $emptyCategory = Category::firstOrCreate(
            ['name' => 'Без категории'],
            ['icon' => '/admin/storage/images/green_dot.png']
        );

        $lineNumber = 0;
        $latitudeColNum = null;
        $longitudeColNum = null;
        $descriptionColNum = null;
        $name = '';
        $builder = '';
        $address = '';

        $file = $request->file('csv');
        $fileContents = file($file->getPathname());

        foreach ($fileContents as $line) {
            $lineNumber++;
            $data = str_getcsv($line);
            $dataArray = explode(';', $data[0]);

            if ($lineNumber === 1) {
                foreach ($dataArray as $colNumber => $colName) {
                    $colName = trim($colName, '\'" ');

                    if (strcasecmp($colName, 'широта') === 0 || strcasecmp($colName, 'Широта') === 0) {
                        $latitudeColNum = $colNumber;
                    } elseif (strcasecmp($colName, 'долгота') === 0 || strcasecmp($colName, 'Долгота') === 0) {
                        $longitudeColNum = $colNumber;
                    } elseif (strcasecmp($colName, 'описание') === 0 || strcasecmp($colName, 'Описание') === 0) {
                        $descriptionColNum = $colNumber;
                    }
                }
            } else {
                $coordinates = [$dataArray[$latitudeColNum], $dataArray[$longitudeColNum]];
                $descriptionArray = explode('/', $dataArray[$descriptionColNum]);

                if (array_key_exists(0, $descriptionArray)) {
                    $name = trim(trim($descriptionArray[0], '\'"'));
                }

                if (array_key_exists(1, $descriptionArray)) {
                    $builder = trim(trim($descriptionArray[1], '\'"'));
                }

                if (array_key_exists(2, $descriptionArray)) {
                    $address = trim(trim($descriptionArray[2], '\'"'));
                }

                Point::create([
                    'image' => '/admin/storage/images/placeholder.jpg',
                    'name' => $name,
                    'address' => $address,
                    'description' => 'Застройщик: ' . $builder,
                    'coordinates' => Json::encode($coordinates),
                    'category_id' => $emptyCategory->id,
                    'is_visible' => 0
                ]);
            }
        }

        return redirect()->route('points.index')->with('message', "Точки из загруженного CSV файла успешно созданы.");
    }

    public function filter(Request $request) {
        $whereArray = [];
        $filters = [];

        if ($request->has('category_id') && $request->filled('category_id')) {
            $whereArray[] = ['category_id', $request->get('category_id')];
            $filters['category_id'] = $request->get('category_id');
        }

        if ($request->has('hasImage') && $request->filled('hasImage')) {
            $whereArray[] = ['hasImage', $request->get('hasImage')];
            $filters['hasImage'] = $request->get('hasImage');
        }

        if ($request->has('isVisible') && $request->filled('isVisible')) {
            $whereArray[] = ['isVisible', $request->get('isVisible')];
            $filters['isVisible'] = $request->get('isVisible');
        }

        return Inertia::render('Admin/Points', [
            'filters' => $filters,
            'points' => Point::where($whereArray)
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->through(fn ($point) => [
                    'id' => $point->id,
                    'image' => $point->image,
                    'name' => $point->name,
                    'address' => $point->address,
                    'description' => $point->description,
                    'tg_link' => $point->tg_link,
                    'youtube_link' => $point->youtube_link,
                    'category' => Category::find($point->category_id)->name,
                    'coordinates' => $point->coordinates,
                    'is_visible' => $point->is_visible,
                    'updated_at' => $point->updated_at->toDateTimeString(),
                ])
        ]);
    }
}
