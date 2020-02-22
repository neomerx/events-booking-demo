<?php

namespace App;

use DateTimeInterface;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int                 event_id
 * @property int                 location_id
 * @property bool                is_active
 * @property string              name
 * @property DateTimeInterface   date_from
 * @property DateTimeInterface   date_to
 * @property-read Location       location
 * @property-read EventSection[] eventSections
 */
final class Event extends Model
{
    /** @inheritdoc */
    protected $table = 'events';

    /** @inheritdoc */
    protected $primaryKey = 'event_id';

    /** @inheritdoc */
    protected $fillable = [
        'location_id',
        'is_active',
        'name',
        'date_from',
        'date_to',
    ];

    /** @inheritdoc */
    protected $dates = [
        'date_from',
        'date_to',
        'created_at',
        'updated_at',
    ];

    /** @inheritdoc */
    protected $casts = [
        'event_id'    => 'integer',
        'location_id' => 'integer',
        'is_active'   => 'boolean',
        'date_from'   => 'datetime',
        'date_to'     => 'datetime',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * @return HasOne
     */
    public function location(): HasOne
    {
        return $this->hasOne(Location::class, 'location_id', 'location_id');
    }

    /**
     * @return HasMany
     */
    public function eventSections(): HasMany
    {
        return $this->hasMany(EventSection::class, 'event_id', 'event_id');
    }

    /**
     * Add ordering by planned date (ASC).
     *
     * @param Builder $query
     *
     * @return Builder
     *
     * @throws Exception
     */
    public function scopeOrderByStartDateAsc(Builder $query): Builder
    {
        return $query->orderBy('events.date_from', 'ASC');
    }

    /**
     * Add condition to select only events in the future.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('events.is_active', '=', true);
    }
}
