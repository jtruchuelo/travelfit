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
    }
}
