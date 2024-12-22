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
 * @property string $category_id;
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
        'category_id',
        'coordinates',
        'is_visible'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public static function getAllPointsJsonForOM($request): string
    {
        $result = [];
        $isAdmin = str_contains($request->headers->get('referer'), 'admin');

        $points = $isAdmin ? Point::all() : Point::all()->where('is_visible', 1);

        foreach ($points as $point) {
            $category = Category::query()->find($point->category_id);
            $categoryIconPath = $category->icon;

            if ($isAdmin) {
                $categoryIconPath = str_replace('/admin', '', $category->icon);
            }

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

            if ($point->image) {
                $image = "<img src='{$point->image}' alt='{$point->name}'>";
            }

            $result[] = [
                'geometry' => [
                    'coordinates' => [$longitude, $latitude],
                    'type' => 'Point',
                ],
                'id' => (string) $point->id,
                'options' => [
                    'iconImageHref' => ".{$categoryIconPath}"
                ],
                'properties' => [
                    'address' => $point->address,
                    'name' => $point->name,
                    'hintContent' => "<b>{$point->name}</b><br>{$point->address}",
                    'image' => $image ?? null,
                    'description' => $descriptionHtml,
                    'tg_link' => $tgLink,
                    'youtube_link' => $youtubeLink,
                    'category_id' => $point->category_id,
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
