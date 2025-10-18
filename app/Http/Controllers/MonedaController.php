<?php

namespace App\Http\Controllers;

use App\Models\Moneda;
use Illuminate\Http\Request;

class MonedaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monedas = Moneda::all();
        return view('monedas.index', compact('monedas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('monedas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|size:3|unique:monedas,codigo',
            'simbolo' => 'required|string|max:10',
            'formato' => 'required|string|max:50',
            'activa' => 'required|boolean'
        ]);

        try {
            Moneda::create($request->all());
            return redirect()->route('monedas.index')
                ->with('success', 'Moneda creada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear la moneda.')
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Moneda $moneda)
    {
        return view('monedas.edit', compact('moneda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Moneda $moneda)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|size:3|unique:monedas,codigo,' . $moneda->id,
            'simbolo' => 'required|string|max:10',
            'formato' => 'required|string|max:50',
            'activa' => 'required|boolean'
        ]);

        try {
            $moneda->update($request->all());
            return redirect()->route('monedas.index')
                ->with('success', 'Moneda actualizada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la moneda.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Moneda $moneda)
    {
        try {
            // Verificar si la moneda está siendo usada
            if ($moneda->ventasOrigen()->exists() || 
                $moneda->ventasDestino()->exists() || 
                $moneda->tiposCambioOrigen()->exists() || 
                $moneda->tiposCambioDestino()->exists()) {
                return back()->with('error', 'No se puede eliminar la moneda porque está siendo usada.');
            }

            $moneda->delete();
            return redirect()->route('monedas.index')
                ->with('success', 'Moneda eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la moneda.');
        }
    }

    /**
     * Cambiar el estado de una moneda.
     */
    public function toggleEstado(Moneda $moneda)
    {
        try {
            $moneda->activa = !$moneda->activa;
            $moneda->save();
            
            return redirect()->route('monedas.index')
                ->with('success', 'Estado de la moneda actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el estado de la moneda.');
        }
    }
}