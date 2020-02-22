<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int    event_section_id
 * @property int    price
 * @property string map_shape
 * @property string map_coordinates
 * @property string map_show_logo_at
 * @property string company_name
 * @property string company_description
 * @property string company_logo_base64
 * @property string contact_name
 * @property string contact_email
 * @property string contact_phone
 */
class EventSectionResource extends JsonResource
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
            'event_section_id'    => $this->event_section_id,
            'price'               => $this->price,
            'map_shape'           => $this->map_shape,
            'map_coordinates'     => $this->map_coordinates,
            'map_show_logo_at'    => $this->map_show_logo_at,
            'company_name'        => $this->company_name,
            'company_description' => $this->company_description,
            'company_logo_base64' => $this->company_logo_base64,
            'contact_name'        => $this->contact_name,
            'contact_email'       => $this->contact_email,
            'contact_phone'       => $this->contact_phone,
        ];
    }
}
