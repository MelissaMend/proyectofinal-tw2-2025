<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Materia;

class MateriaController extends Controller
{
    public function index(){
        $materias = Materia::all();
        return view('materias.index', compact('materias'));
    }
    public function create(){
        return view('materias.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:materias'
        ], [
            'nombre.required' => 'El nombre de la materia es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'nombre.unique' => 'Esta materia ya existe.'
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
            Materia::create([
                'nombre' => $request->nombre
            ]);
            if( $request->ajax() ){
                return response()->json([
                    'success' => true,
                    'message' => 'Materia creada correctamente'
                ]);
            }
            return redirect()
            ->route('materias.index')
            ->with('success', 'Materia creada correctamente');
        }
        catch( \Exception $e ){
            if( $request->ajax() ){
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la materia'
                ], 500);
            }
            return redirect()
            ->back()
            ->with('error', 'Error al crear la materia')
            ->withInput();
        }
    }
    public function edit($id){
        $materia = Materia::findOrFail($id);
        return view('materias.edit', compact('materia'));
    }
    public function update(Request $request, $id){
        $materia = Materia::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:materias,nombre,' . $id
        ], [
            'nombre.required' => 'El nombre de la materia es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'nombre.unique' => 'Esta materia ya existe.'
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
            $materia->update([
                'nombre' => $request->nombre
            ]);
            if( $request->ajax() ){
                return response()->json([
                    'success' => true,
                    'message' => 'Materia actualizada correctamente'
                ]);
            }
            return redirect()
            ->route('materias.index')
            ->with('success', 'Materia actualizada correctamente');
        }
        catch( \Exception $e ){
            if( $request->ajax() ){
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la materia'
                ], 500);
            }
            return redirect()
            ->back()
            ->with('error', 'Error al actualizar la materia')
            ->withInput();
        }
    }
    public function destroy($id){
        try{
            $materia = Materia::findOrFail($id);  
            $materia->delete();  
            return redirect()
            ->route('materias.index')
            ->with('success', 'Materia eliminada correctamente');
        }
        catch( \Exception $e ){
            return redirect()
            ->route('materias.index')
            ->with('error', 'Error al eliminar la materia');
        }
    }
}