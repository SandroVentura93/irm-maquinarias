<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Region;
use App\Models\Provincia;
use App\Models\Distrito;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $clientes = Cliente::with(['region', 'provincia', 'distrito'])->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $regiones = Region::all();
        return view('clientes.create', compact('regiones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'dni' => 'nullable|digits:8|unique:clientes,dni',
            'ruc' => 'nullable|digits:11|unique:clientes,ruc',
            'email' => 'nullable|email|unique:clientes,email'
        ]);

        Cliente::create($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente registrado exitosamente');
    }

    public function edit(Cliente $cliente)
    {
        $regiones = Region::all();
        $provincias = $cliente->region ? Provincia::where('region_id', $cliente->region_id)->get() : [];
        $distritos = $cliente->provincia ? Distrito::where('provincia_id', $cliente->provincia_id)->get() : [];
        
        return view('clientes.edit', compact('cliente', 'regiones', 'provincias', 'distritos'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required',
            'dni' => 'nullable|digits:8|unique:clientes,dni,' . $cliente->id,
            'ruc' => 'nullable|digits:11|unique:clientes,ruc,' . $cliente->id,
            'email' => 'nullable|email|unique:clientes,email,' . $cliente->id
        ]);

        $cliente->update($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente');
    }

    /**
     * Buscar clientes para autocompletado AJAX
     */
    public function buscar(Request $request): \Illuminate\Http\JsonResponse
    {
        $q = $request->query('q','');
        if (empty($q)) {
            $clientes = Cliente::limit(50)->get(['id','nombre','documento','telefono','direccion']);
        } else {
            $clientes = Cliente::where('nombre','LIKE',"%{$q}%")
                ->orWhere('documento','LIKE', "%{$q}%")
                ->limit(10)
                ->get(['id','nombre','documento','telefono','direccion']);
        }
        return response()->json($clientes);
    }

    /**
     * Registrar cliente por AJAX
     */
    public function storeAjax(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'nullable|string|max:100',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
        ]);

        $cliente = Cliente::create($validated);
        return response()->json($cliente);
    }

    // AJAX: obtener provincias por region
    public function provinciasByRegion($regionId)
    {
        $provincias = Provincia::where('region_id', $regionId)->get();
        return response()->json($provincias);
    }

    // AJAX: obtener distritos por provincia
    public function distritosByProvincia($provinciaId)
    {
        $distritos = Distrito::where('provincia_id', $provinciaId)->get();
        return response()->json($distritos);
    }
}