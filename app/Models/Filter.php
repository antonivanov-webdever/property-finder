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
}
