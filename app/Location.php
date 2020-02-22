<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                               location_id
 * @property string                            name
 * @property string                            address
 * @property string                            location
 * @property string                            map_image_base64
 * @property-read LocationSection[]|Collection locationSections
 */
final class Location extends Model
{
    /** @inheritdoc */
    protected $table = 'locations';

    /** @inheritdoc */
    protected $primaryKey = 'location_id';

    /** @inheritdoc */
    protected $fillable = [
        'name',
        'address',
        'map_image_base64',
    ];

    /** @inheritdoc */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /** @inheritdoc */
    protected $casts = [
        'location_id' => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * @return HasMany
     */
    public function locationSections(): HasMany
    {
        return $this->hasMany(LocationSection::class, 'location_id', 'location_id');
    }
}
