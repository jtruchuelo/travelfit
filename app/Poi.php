<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poi extends Model
{
    protected $table = 'pois';

    protected $fillable = [
        'name', 'idApi', 'startDate', 'destination_id', 'duration', 'photo', 'location', 'description'
    ];

    public function destination(){
        return $this->belongsTo('App\Destination', 'id');
    }
}
