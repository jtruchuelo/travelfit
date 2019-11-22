<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DestinationResource;

class ItineraryResource extends JsonResource
{
    //public $preserveKeys = true;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'itinerary_id' => $this->id,
            'name' => $this->name,
            'createdDate' => (string) $this->createdDate,
            'startDate' => (string) $this->startDate,
            'endDate' => (string) $this->endDate,
            'public' => $this->public,
            'user_id' => $this->user_id,
            'destinations' => DestinationResource::collection($this->destinations),
        ];
    }
}
