<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Tipo;

class UsuarioController extends Controller
{
    public function index(){
        $usuarioActual = Auth::user();
        $tipoUsuario = $usuarioActual->tipo->tipo;
        // Consulta base con relación tipo
        $query = User::with('tipo');
        //los admin miran todo
        if ( $tipoUsuario === 'admin') {
            $usuarios = $query->get();
        }
        //el profesor solo mira a los estudiantes
        elseif ( $tipoUsuario === 'profesor' ) {
            $usuarios = $query->whereHas('tipo', function($q) {
                $q->where('tipo', 'estudiante');
            })->get();
        }
        //los estudiantes no pueden entrar a esta seccion
        else {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
        return view('usuarios.index', compact('tipos'));
    }

    public function create(){
        $tipos = Tipo::all();
        return view('usuarios.create', compact('tipos'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'tipos_id' => 'required|exists:tipos,id'
        ],
        [
            'username.required' => 'El username es obligatorio',
            'username.unique' => 'Este username ya está en uso',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está en uso',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 carateres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'tipos_id.required' => 'Debe seleccionar un tipo de usuario',
            'tipos_id.exists' => 'El tipo de usuario seleccionado no es válido'
        ]);
        if ( $validator->fails() ) {
            if ( $request->ajax() ) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
        }
        try {
            User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tipos_id' => $request->tipos_id,
            ]);
            if ( $request->ajax() ){
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario creado correctamente'
                ]);
            }
            return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente');
        } 
        catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear usuario'
                ], 500);
            }
            return redirect()
            ->back()
            ->with('error', 'Error al crear el usuario')
            -withInput();
        }
    }

    public function edit($id){
        $usuarios = User::findOrFail($id);
        $tipos = Tipo::all();
        return view('usuarios.edit', compact('usuario', 'tipos'));
    }

    public function update(Request $request, $id){
        $usuario = User::findOrFail($id);
        //reglas de validación
        $rules = [
            'username' => 'required|string|max:255|unique:users,username,' .$id,
            'email' => 'required|string|email|max:255|unique:users,email,' .$id,
            'tipos_id' => 'required|exists:tipos,id'
        ];
        //para cambiar contra
        if ( $request->filled('password') ){
            $rules['password'] = 'required|string|min:6|confirmed';
        }
        $messages = [
            'username.required' => 'El username es obligatorio',
            'username.unique' => 'Este username ya está en uso',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está en uso',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 carateres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'tipos_id.required' => 'Debe seleccionar un tipo de usuario',
            'tipos_id.exists' => 'El tipo de usuario seleccionado no es válido'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ( $validator->fails() ){
            if ( $request->ajax ){
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()
            ->back()
            ->with($validator)
            -withInput();
        }
        try{
            $data = [
                'username' => $request->username,
                'email' => $request->email,
                'tipos_id' => $request->tipos_id,
            ];
            
            if( $request->filled('password') ){
                $data['password'] = Hash::make($request->password);
            }
            $usuario->update($data);
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario actualizado correctamente'
                ]);
            }
            return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente');
        }
        catch (\Exception $e) {
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el usuario'
                ], 50);
            }
            return redirect()
            ->back()
            ->with('error', 'Error al actualizar el usuario')
            -withInput();
        }
    }

    public function destroy($id){
        try{
            $usuario = User::findOrFail($id);
            $usuario->delete();

            return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente');
        }
        catch( \Exception $e){
            return redirect()
            ->route('usuarios.index')
            ->with('error', 'Error al eliminar el usuario');
        }
    }
}

