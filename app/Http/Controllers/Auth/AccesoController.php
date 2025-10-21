<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccesoController extends Controller
{
    public function mostrarFormulario(){
        return view('auth.acceso');
    }

    public function iniciarSesion(Request $request){
        $validator=Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6'
        ],[
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico es válido',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres',
            'password.required' => 'La contraseña es obligatoria',
            'password.string' => 'La contraseña debe ser una cadena de texto',
            'password.min' => 'La contraseña debe tener al menos 6 carateres',
        ]);

        if ($validator->fails()) {
            if( $request->ajax() ){
                return response()>json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }

        $credenciales = $request->only('email', 'password');

        if ( Auth::attempt( $credenciales ) ) {
            $request->session()->regenerate();
            $usuario = Auth::user();

            if( $request->ajax() ){
                return response()>json([
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso',
                    'redirect' => ''
                ]);
            }
        }

    }
}
