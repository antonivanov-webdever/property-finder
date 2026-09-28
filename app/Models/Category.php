<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id;
 * @property string $name;
 * @property string $icon;
 * @property-read string $created_at;
 * @property-read string $updated_at;
 */

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public static function getFormattedCategories(): array
    {
        $categories = self::orderBy('updated_at', 'desc')->get();
        $formatted_categories = [];

        foreach ($categories as $key => $category) {
            $formatted_categories[$key] = $category->toArray();
            $formatted_categories[$key]['updated_at'] = $category->updated_at->toDateTimeString();

            unset($formatted_categories[$key]['category_id']);
            unset($formatted_categories[$key]['created_at']);
        }

        return $formatted_categories;
    }
}
