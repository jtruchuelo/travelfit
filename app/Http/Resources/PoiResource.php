<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PoiResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'idApi' => $this->idApi,
            'startDate' => (string) $this->startDate,
            'endDate' => (string) $this->endDate,
            'destinationId' => $this->destination_id,
        ];
    }
}
