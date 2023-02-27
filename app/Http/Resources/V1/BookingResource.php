<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'autoId' => $this->auto_id,
            'emploeeId' => $this->emploee_id,
            'bookingFrom' => $this->booking_from,
            'bookingTo' => $this->booking_to
        ];
    }
}
