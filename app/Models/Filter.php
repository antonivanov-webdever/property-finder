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

class Filter extends Model
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

    public static function getFormattedFilters(): array
    {
        $filters = self::orderBy('updated_at', 'desc')->get();
        $formatted_filters = [];

        foreach ($filters as $key => $filter) {
            $formatted_filters[$key] = $filter->toArray();
            $formatted_filters[$key]['updated_at'] = $filter->updated_at->toDateTimeString();

            unset($formatted_filters[$key]['filter_id']);
            unset($formatted_filters[$key]['created_at']);
        }

        return $formatted_filters;
    }
}
