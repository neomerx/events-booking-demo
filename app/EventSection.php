<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int        event_section_id
 * @property int        event_id
 * @property int        location_section_id
 * @property int        price
 * @property string     company_name
 * @property string     company_description
 * @property string     company_logo_base64
 * @property string     contact_name
 * @property string     contact_email
 * @property string     contact_phone
 * @property-read Event event
 */
final class EventSection extends Model
{
    /** @inheritdoc */
    protected $table = 'event_sections';

    /** @inheritdoc */
    protected $primaryKey = 'event_section_id';

    /** @inheritdoc */
    protected $fillable = [
        'event_id',
        'location_section_id',
        'price',
        'company_name',
        'company_description',
        'company_logo_base64',
        'contact_name',
        'contact_email',
        'contact_phone',
    ];

    /** @inheritdoc */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /** @inheritdoc */
    protected $casts = [
        'event_section_id'    => 'integer',
        'event_id'            => 'integer',
        'location_section_id' => 'integer',
        'price'               => 'integer', // yes, integer, to avoid floating errors. Use it as dollars, cents, euros, etc
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];

    /**
     * @return HasOne
     */
    public function event(): HasOne
    {
        return $this->hasOne(Event::class, 'event_id', 'event_id');
    }
}
