<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'id' => $this->id,
            'name' => $this->name,
            'createdDate' => (string) $this->createdDate,
            'startDate' => (string) $this->startDate,
            'endDate' => (string) $this->endDate,
            'public' => $this->public,
            'userId' => $this->user_id,
        ];
    }
}
