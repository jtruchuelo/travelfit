<?php

namespace App\Http\Resources;

// use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\User;

class ItineraryResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    // public $collects = 'App\Http\Resources\ItineraryResource';

    public function toArray($request)
    {
        return [
            'status' => 'success',
            'itineraries' => $this->collection
        ];

        /* $username = User::select('name')->where(['id' => $this->user_id])->pluck('name');
        return [
            'itinerary_id' => $this->id,
            'name' => $this->name,
            'createdDate' => (string) $this->createdDate,
            'startDate' => (string) $this->startDate,
            'endDate' => (string) $this->endDate,
            'isPublic' => $this->isPublic,
            'user_id' => $this->user_id,
            'user_name' => $username[0],
        ]; */
    }
}
