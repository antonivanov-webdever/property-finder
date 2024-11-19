<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psy\Util\Json;

/**
 * @property-read int $id;
 * @property string $image;
 * @property string $name;
 * @property string $address;
 * @property string $description;
 * @property string $tg_link;
 * @property string $youtube_link;
 * @property string $filter_id;
 * @property string $coordinates;
 * @property string $is_visible;
 * @property-read string $created_at;
 * @property-read string $updated_at;
 */

class Point extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'address',
        'description',
        'tg_link',
        'youtube_link',
        'filter_id',
        'coordinates',
        'is_visible'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public static function getFormattedPoints(): array
    {
        $points = self::orderBy('updated_at', 'desc')->get();
        $formatted_points = [];

        foreach ($points as $key => $point) {
            $formatted_points[$key] = $point->toArray();

            $formatted_points[$key]['filter'] = Filter::find($point->filter_id)->name;
            $formatted_points[$key]['updated_at'] = $point->updated_at->toDateTimeString();

            unset($formatted_points[$key]['filter_id']);
            unset($formatted_points[$key]['created_at']);
        }

        return $formatted_points;
    }

    public static function getAllPointsJsonForOM(): string
    {
        $points = Point::all();
        $result = [];

        foreach ($points as $point) {
            $filter = Filter::query()->find($point->filter_id);
            $filterIconPath = str_replace('/admin', '', $filter->icon);
            $coordinatesArray = json_decode($point->coordinates);
            $longitude = $coordinatesArray[0];
            $latitude = $coordinatesArray[1];
            $description = preg_replace('/\r\n/', ';', htmlspecialchars($point->description));
            $descriptionArray = explode(';', $description);
            $descriptionHtml = '';
            $tgLink = null;
            $youtubeLink = null;

            foreach ($descriptionArray as $descriptionItem) {
                $descriptionItemArray = explode(':', $descriptionItem);
                $descriptionHtml .= '<div><span>' . $descriptionItemArray[0] . ':</span> ' . $descriptionItemArray[1] . '</div>';
            }

            if ($point->tg_link) {
                $tgLink = '<a href="' . $point->tg_link . '" target="_blank">Обсудить в Telegram</a>';
            }

            if ($point->youtube_link) {
                $youtubeLink = '<a href="' . $point->youtube_link . '" target="_blank">Посмотреть на YouTube</a>';
            }

            $result[] = [
                'geometry' => [
                    'coordinates' => [$longitude, $latitude],
                    'type' => 'Point',
                ],
                'id' => (string) $point->id,
                'options' => [
                    'iconImageHref' => ".{$filterIconPath}"
                ],
                'properties' => [
                    'address' => $point->address,
                    'name' => $point->name,
                    'hintContent' => "<b>{$point->name}</b><br>{$point->address}",
                    'image' => "<img src='{$point->image}' alt='{$point->name}'>",
                    'description' => $descriptionHtml,
                    'tg_link' => $tgLink,
                    'youtube_link' => $youtubeLink,
                    'filter_id' => $point->filter_id,
                ],
                'type' => 'Feature',
            ];
        }

        return Json::encode([
            'features' => $result,
            'type' => 'FeatureCollection'
        ]);
    }
}
