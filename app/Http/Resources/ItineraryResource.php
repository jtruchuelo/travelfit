<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DestinationResource;
use App\User;

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
        $username = User::select('name')->where(['id' => $this->user_id])->pluck('name');
        return [
            'itinerary_id' => $this->id,
            'name' => $this->name,
            'createdDate' => (string) $this->createdDate,
            'startDate' => (string) $this->startDate,
            'endDate' => (string) $this->endDate,
            'isPublic' => $this->isPublic,
            'user_id' => $this->user_id,
            // 'user_name' => 'pedro',
            'user_name' => $username[0],
            'destinations' => DestinationResource::collection($this->destinations),
        ];
    }
}
