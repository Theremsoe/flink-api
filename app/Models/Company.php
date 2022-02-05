<?php

namespace App\Models;

use App\Models\Support\DateFormatteable;
use App\Models\Support\Normalizable;
use App\Models\Support\UuidIdentifiable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string                                          $id
 * @property string                                          $name
 * @property string                                          $description
 * @property string                                          $symbol
 * @property \Illuminate\Database\Eloquent\Casts\ArrayObject $market
 * @property \Illuminate\Support\Carbon                      $created_at
 * @property \Illuminate\Support\Carbon                      $updated_at
 * @property null|\Illuminate\Support\Carbon                 $deleted_at
 *
 * @method static \Database\Factories\CompanyFactory factory()
 * @method static self make()
 */
class Company extends Model
{
    use DateFormatteable;
    use HasFactory;
    use Normalizable;
    use SoftDeletes;
    use UuidIdentifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'symbol',
        'market',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'market' => AsArrayObject::class,
    ];
}
