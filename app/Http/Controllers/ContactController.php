<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

/*
Se ha implmentado la funcionalidad para recibir y controlar los emails de contacto, pero no se ha implementado como si fueran email de verdad,
pues esto requiere de la instalación adicional de un servidor de correo de salida para enviar los correos electrónicos recibidos.
 */

class ContactController extends Controller
{
    public function contact (Request $request) {

        $rules = [
            'name' => 'required|alpha|string',
            'email' => 'required|email|string',
            'subject' => 'required',
            'message' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $respuesta = Array (
                'code' => 400 ,
                'status' => 'error',
                'message' => $validator->messages(),
            );
        } else {
            $respuesta = Array (
                'code' => 200 ,
                'status' => 'success',
                'message' => 'Mensaje recibido correctamente',
            );
        }

        return response()->json($respuesta, $respuesta['code']);
    }
}
