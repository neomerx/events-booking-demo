<?php

namespace App\Http\Resources;

use DateTimeInterface;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int               event_id
 * @property bool              is_active
 * @property string            name
 * @property DateTimeInterface date_from
 * @property DateTimeInterface date_to
 * @property string            address
 * @property float             latitude
 * @property float             longitude
 */
class EventMarkerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'event_id'  => $this->event_id,
            'is_active' => $this->is_active,
            'name'      => $this->name,
            'date_from' => $this->date_from,
            'date_to'   => $this->date_to,
            'address'   => $this->address,
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
