<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $table = 'destinations';

    protected $fillable = [
        'name', 'idApi', 'startDate', 'endDate', 'itinerary_id', 'location', 'photo'
    ];


    public function pois(){
        return $this->hasMany('App\Poi', 'destination_id');
    }

    public function itinerary(){
        return $this->belongsTo('App\Itinerary', 'id');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($destination) {
             $destination->pois()->delete();
        });
    }
}
