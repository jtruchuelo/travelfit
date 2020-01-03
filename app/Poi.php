<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poi extends Model
{
    protected $table = 'pois';

    protected $fillable = [
        // 'name', 'idApi', 'startDate', 'endDate', 'destination_id',
        'name', 'idApi', 'startDate', 'destination_id', 'duration', 'photo', 'location',
    ];

    public function destination(){
        return $this->belongsTo('App\Destination', 'id');
    }
}
