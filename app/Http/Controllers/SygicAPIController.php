<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SygicAPI;
use DateTime;
use DateInterval;
use stdClass;

class SygicAPIController extends Controller
{
    protected $sygicApi;

    public function __construct(SygicAPI $sygicApi)
    {
    	$this->sygicApi = $sygicApi;
    }

    public function new(Request $request)
    {
        // Obtengo datos recibidos front
        $itinerary = $request->new_itinerary;
        $destination = $itinerary['destinations'][0];
        $categories = $request->categories;

        // Obtengo datos del destino a traves del API Sygic
        $dataDestination = $this->sygicApi->getDestinationData($destination['name']);

        // Actualizo los datos del destino e itinerario
        $destination['id'] = 1;
        $destination['name'] = $dataDestination->data->places[0]->name_translated != "" ? $dataDestination->data->places[0]->name_translated : $dataDestination->data->places[0]->name;
        $destination['location'] = $dataDestination->data->places[0]->location;
        $destination['idApi'] = $dataDestination->data->places[0]->id;
        $destination['startDate'] = date('Y-m-d h:i:s', strtotime($destination['startDate']));
        $destination['endDate'] = date('Y-m-d h:i:s', strtotime($destination['endDate']));
        $destination['photo'] = $this->sygicApi->getDetails($destination['idApi']);
        $destination['itinerary_id'] = $itinerary['id'];
        $itinerary['createdDate'] = date('Y-m-d h:i:s', strtotime($itinerary['createdDate']));
        $itinerary['startDate'] = $destination['startDate'];
        $itinerary['endDate'] = $destination['endDate'];

        // Comienzo a buscar los POI del destino
        // Cargo las categorías que hayan sido seleccionadas
        $searchCategories = [];
        if ($categories['discovering'] == true) $searchCategories[] = 'discovering';
        // if ($categories['eating'] == true) $searchCategories[] = 'eating';
        // if ($categories['going_out'] == true) $searchCategories[] = 'going_out';
        if ($categories['hiking'] == true) $searchCategories[] = 'hiking';
        if ($categories['playing'] == true) $searchCategories[] = 'playing';
        if ($categories['shopping'] == true) $searchCategories[] = 'shopping';
        if ($categories['sightseeing'] == true) $searchCategories[] = 'sightseeing';
        if ($categories['doing_sports'] == true) $searchCategories[] = 'doing_sports';
        // var_dump(implode('|', $searchCategories));die();

        // Calculo el número de días para saber cuantos días debe rellenar.
        $startDate = new DateTime($destination['startDate']);
        $endDate = new DateTime($destination['endDate']);
        $numDays = $endDate->diff($startDate)->format("%a");

        $factor = 3; // factor de activades / num. dias itinerario

        // Obtengo los POIS del destino
        $dataPois = $this->sygicApi->getDestinationPois($destination['idApi'], $searchCategories, $factor*$numDays)->data->places;

        // Relleno los días del destino
        $destination['pois'] = $this->addDestinationPois($dataPois, $startDate, $numDays, $destination['id']);
        $itinerary['destinations'] = $destination;

        // Devuelvo el itinerario
        return response()->json($itinerary, 200);
    }

    private function addDestinationPois (Array $poisList, DateTime $startDate, int $numDays, int $destination_id) {

        $hora = 10;
        $actualDate = $startDate;
        foreach ($poisList as $poi) {
            if ($numDays >= 0) {

                $object = new stdClass();
                $object->id = '';
                $object->destination_id = $destination_id;
                $object->name = $poi->name;
                $object->idApi = $poi->id;
                $object->location = $poi->location;
                $object->duration = $poi->duration_estimate;
                $object->photo = $poi->thumbnail_url;
                $object->description = $poi->perex;

                if($object->duration > 3600) {
                    if ($hora == 10) {
                        $actualDate->setTime($hora, 00);
                        $object->startDate = $actualDate->format('Y-m-d H:i:s');
                        $hora = 16;
                    } else if ($hora == 12) {
                        $hora = 16;
                        $actualDate->setTime($hora, 00);
                        $object->startDate = $actualDate->format('Y-m-d H:i:s');
                        $hora = 10;
                        $actualDate->add(new DateInterval('P1D'));
                        --$numDays;
                    } else if ($hora == 16) {
                        $actualDate->setTime($hora, 00);
                        $object->startDate = $actualDate->format('Y-m-d H:i:s');
                        $hora = 10;
                        $actualDate->add(new DateInterval('P1D'));
                        --$numDays;
                    }
                } else if ($object->duration <= 3600) {
                    if ($hora == 10) {
                        $actualDate->setTime($hora, 00);
                        $object->startDate = $actualDate->format('Y-m-d H:i:s');
                        $hora = 12;
                    } else if ($hora == 12) {
                        $actualDate->setTime($hora, 00);
                        $object->startDate = $actualDate->format('Y-m-d H:i:s');
                        $hora = 16;
                    } else if ($hora == 16) {
                        $actualDate->setTime($hora, 00);
                        $object->startDate = $actualDate->format('Y-m-d H:i:s');
                        $hora = 10;
                        $actualDate->add(new DateInterval('P1D'));
                        --$numDays;
                    }
                }

                $pois[] = $object;
            }
        }

        // Devolver Array
        return $pois;
    }
}
