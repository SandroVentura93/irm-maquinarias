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

    public function buscar(Request $request)
    {
        $query = $request->get('q');
        $clientes = Cliente::where('nombre', 'LIKE', "%{$query}%")
            ->orWhere('apellido', 'LIKE', "%{$query}%")
            ->orWhere('dni', 'LIKE', "%{$query}%")
            ->orWhere('ruc', 'LIKE', "%{$query}%")
            ->get();
            
        return response()->json($clientes);
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