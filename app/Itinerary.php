<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $table = 'itineraries';

    protected $fillable = [
        'name', 'createdDate','startDate', 'endDate', 'isPublic', 'user_id',
    ];

    public function destinations(){
        return $this->hasMany('App\Destination' , 'itinerary_id');
    }

    public function pois(){
        return $this->hasManyThrough('App\Poi', 'App\Destination', 'itinerary_id', 'destination_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'id');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($itinerary) {
            $itinerary->pois()->delete();
            $itinerary->destinations()->delete();
        });
    }
}
