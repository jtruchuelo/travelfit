<?php

namespace App\Http\Controllers;

use App\Itinerary;
use App\Poi;
use App\Destination;
use App\User;

use Illuminate\Http\Request;

class PruebasController extends Controller
/*{
    // Prueba ORM
    public function testOrm() {
        $users = User::all();
        var_dump($users);
        foreach ($users as $user) {
            var_dump($user);
            echo "hola ".$user->exists;
        }

        // $itineraries = Itinerary::all();
        // $pepe = 0;
        // foreach($itineraries as $itinerary) {

        //     echo "</br> Adios ".$pepe;
        //     var_dump($itinerary->pois());
        //     $pepe++;
        // }
        die();

    }

}*/

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Debe retornar la lista de todos los objetos
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Almacenar instancia del objeto que recibe, peticion tipo post
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Itinerary $itinerario)
    {
        //Devuelve una instancia a partir del id.
        //Laravel resuelve el objeto que recibe como parámetro y sabe su id
        // Inyección implicita del modelo.
        // Y resuelve a través de la petición de la URL que manda un id
        // Por eso pongo un objeto a recibir.
        // Tambien podría poner un $id.

        // Borrar este código abajo, solo era prueba orm
        //Solicitamos al modelo el Pokemon con el id solicitado por GET.
        //return Itinerary::where('id', $id)->get();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Itinerary $itinerario)
    {
        //
        // Actualizar instancia existente, peticion put
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Itinerary $itinerario)
    {
        // Eliminar instancia que tiene.
    }
}
