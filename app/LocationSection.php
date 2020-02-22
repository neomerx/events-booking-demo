<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int                            location_section_id
 * @property int                            location_id
 * @property string                         name
 * @property string                         map_show_logo_at
 * @property string                         map_shape
 * @property string                         map_coordinates
 * @property-read EventSection[]|Collection eventSections
 */
final class LocationSection extends Model
{
    /** @inheritdoc */
    protected $table = 'location_sections';

    /** @inheritdoc */
    protected $primaryKey = 'location_section_id';

    /** @inheritdoc */
    protected $fillable = [
        'location_id',
        'name',
        'map_show_logo_at',
        'map_shape',
        'map_coordinates',
    ];

    /** @inheritdoc */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /** @inheritdoc */
    protected $casts = [
        'location_section_id' => 'integer',
        'location_id'         => 'integer',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];
}
