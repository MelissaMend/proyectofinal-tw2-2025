<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Materia;
use App\Models\Calificacion;
use App\Models\MateriasXUsuario;

class CalificacionController extends Controller
{
    public function index($usuario_id, $materia_id){
        $usuario = User::with('tipo')->findOrFail($usuario_id);
        $materia = Materia::findOrFail($materia_id);
        // Verificar que la materia esté asignada al usuario
        $asignacion = MateriasXUsuario::where('users_id', $usuario_id)
        ->where('materias_id', $materia_id)
        ->first();
        if( !$asignacion ){
            return redirect()->route('materiasxusuario.index', $usuario_id)
            ->with('error', 'Esta materia no está asignada al usuario');
        }

        // Obtener calificaciones usando la relación correcta
        $calificaciones = Calificacion::where('materias_x_usuarios_id', $asignacion->id)
        ->orderBy('id', 'desc')
        ->get();
        // Calcular promedio
            $promedio = $calificaciones->avg('calificacion');
        $promedio = $promedio ? round($promedio, 2) : 0;
        return view('calificaciones.index', compact('usuario', 'materia', 'calificaciones', 'promedio', 'asignacion'));
    }
    public function store(Request $request, $usuario_id, $materia_id){
        $validator = Validator::make($request->all(), [
            'calificacion' => 'required|numeric|min:0|max:10'
        ], [
            'calificacion.required' => 'La calificación es obligatoria.',
            'calificacion.numeric' => 'La calificación debe ser un número.',
            'calificacion.min' => 'La calificación mínima es 0.',
            'calificacion.max' => 'La calificación máxima es 10.'
        ]);
        if( $validator->fails() ){
            if( $request->ajax() ){
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
        try{
            //obtener asignacion materiasxusuarios
            $asignacion = MateriasXUsuario::where('users_id', $usuario_id)
            ->where('materias_id', $materia_id)
            ->first();
            if( !$asignacion ){
                throw new \Exception('La materia no está asignada al usuario');
            }
            Calificacion::create([
                'materias_x_usuarios_id' => $asignacion->id,
                'calificacion' => $request->calificacion
            ]);
            if( $request->ajax() ){
                return response()->json([
                    'success' => true,
                    'message' => 'Calificación agregada correctamente'
                ]);
            }
            return redirect()
            ->route('calificaciones.index', [$usuario_id, $materia_id])
            ->with('success', 'Calificación agregada correctamente');
        }
        catch( \Exception $e ){
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al agregar la calificación: ' . $e->getMessage()
                ], 500);
            }
            return redirect()
            ->back()
            ->with('error', 'Error al agregar la calificación')
            ->withInput();
        }
    }
    public function edit($id){
        $calificacion = Calificacion::with(['materiasXUsuario.user', 'materiasXUsuario.materia'])->findOrFail($id);
        return view('calificaciones.edit', compact('calificacion'));
    }
    public function update(Request $request, $id){
        $calificacion = Calificacion::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'calificacion' => 'required|numeric|min:0|max:10'
        ], [
            'calificacion.required' => 'La calificación es obligatoria.',
            'calificacion.numeric' => 'La calificación debe ser un número.',
            'calificacion.min' => 'La calificación mínima es 0.',
            'calificacion.max' => 'La calificación máxima es 10.'
        ]);
        if( $validator->fails() ){
            if( $request->ajax() ){
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
        try{
            $calificacion->update([
                'calificacion' => $request->calificacion
            ]);
            if( $request->ajax() ){
                return response()->json([
                    'success' => true,
                    'message' => 'Calificación actualizada correctamente'
                ]);
            }
            // Obtener usuario_id y materia_id para redireccionar
            $asignacion = $calificacion->materiasXUsuario;
            return redirect()
            ->route('calificaciones.index', [$asignacion->users_id, $asignacion->materias_id])
            ->with('success', 'Calificación actualizada correctamente');
                           
        }
        catch( \Exception $e ){
            if( $request->ajax() ){
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la calificación'
                ], 500);
            }
            return redirect()
            ->back()
            ->with('error', 'Error al actualizar la calificación')
            ->withInput();
        }
    }
    public function destroy($id){
        try{
            $calificacion = Calificacion::with('materiasXUsuario')->findOrFail($id);
            $asignacion = $calificacion->materiasXUsuario;
            $usuario_id = $asignacion->users_id;
            $materia_id = $asignacion->materias_id;
            $calificacion->delete();
            return redirect()
            ->route('calificaciones.index', [$usuario_id, $materia_id])
            ->with('success', 'Calificación eliminada correctamente');
        }
        catch( \Exception $e ){
            return redirect()
            ->back()
            ->with('error', 'Error al eliminar la calificación');
        }
    }
}