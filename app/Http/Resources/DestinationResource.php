<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PoiResource;

class DestinationResource extends JsonResource
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
            'destination_id' => $this->id,
            'name' => $this->name,
            'idApi' => $this->idApi,
            'startDate' => (string) $this->startDate,
            'endDate' => (string) $this->endDate,
            // 'itinerary_id' => $this->itinerary_id,
            'pois' => PoiResource::collection($this->pois),
        ];
    }
}
