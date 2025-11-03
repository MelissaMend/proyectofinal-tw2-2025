<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Tipo;  //tablaque instancio

class TipoController extends Controller
{
    public function index(){
        $tipos = Tipo::all();
        return view('tipos.index', compact('tipos'));
    }

    public function create(){
        return view('tipos.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|string|max:50|unique:tipos',
        ],
        [
            'tipo.required' => 'El tipo es obligatorio',
            'tipo.max' => 'El tipo no puede tener más de 50 caracteres',
            'tipo.unique' => 'Este tipo ya existe'
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
                Tipo::create([
                    'tipo' => $request->tipo
                ]);
                if ( $request->ajax() ){
                    return response()->json([
                        'success' => true,
                        'message' => 'Tipo creado correctamente'
                    ]);
                }
                return redirect()
                ->route('tipos.index')
                ->with('success', 'Tipo creado correctamente');
            } 
            catch (\Exception $e) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al crear el tipo de usuario'
                    ], 500);
                }
                return redirect()
                ->back()
                ->with('error', 'Error al crear el tipo de usuario')
                -withInput();
            }
        }
    
    public function edit($id){
      $tipo = Tipo::findOrFail($id); 
     return view('tipos.edit', compact('tipo'));
    }

    public function update(Request $request, $id){
        $tipo = Tipo::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tipo' => 'required|string|max:50|unique:tipos',
        ],
        [
            'tipo.required' => 'El tipo es obligatorio',
            'tipo.max' => 'El tipo no puede tener más de 50 caracteres',
            'tipo.unique' => 'Este tipo ya existe'
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
                $tipo->update([
                    'tipo' => $request->tipo
                ]);
                if ( $request->ajax() ){
                    return response()->json([
                        'success' => true,
                        'message' => 'Tipo actualizado correctamente'
                    ]);
                }
                return redirect()
                ->route('tipos.index')
                ->with('success', 'Tipo actualizado correctamente');
            } 
            catch (\Exception $e) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al actualizar el tipo de usuario'
                    ], 500);
                }
                return redirect()
                ->back()
                ->with('error', 'Error al actualizar el tipo de usuario')
                -withInput();
            }
        }

    public function destroy($id){
         try{
            $tipo = Tipo::findOrFail($id);

            if ( $tipo->users()->count() > 0 ) {
                return redirect()
                ->route('tipos.index')
                ->with ('error', 'No se puede eliminar el tipo porque tienes usuarios asignados');
            }
            $tipo->delete();

            return redirect()
            ->route('tipos.index')
            ->with('success', 'Tipo eliminado correctamente');
        }
        catch( \Exception $e){
            return redirect()
            ->route('tipos.index')
            ->with('error', 'Error al eliminar el tipo');
        }
    }
}
