<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
