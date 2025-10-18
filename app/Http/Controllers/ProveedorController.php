<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $proveedores = Proveedor::paginate(10);
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_razon_social' => 'required',
            'dni_ruc' => 'nullable|unique:proveedores,dni_ruc',
            'email' => 'nullable|email'
        ]);

        Proveedor::create($request->all());
        return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado exitosamente');
    }

    public function show(Proveedor $proveedor)
    {
        $proveedor->load('productos');
        return view('proveedores.show', compact('proveedor'));
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre_razon_social' => 'required',
            'dni_ruc' => 'nullable|unique:proveedores,dni_ruc,' . $proveedor->id,
            'email' => 'nullable|email'
        ]);

        $proveedor->update($request->all());
        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado exitosamente');
    }

    public function productos(Proveedor $proveedor)
    {
        $productos = Producto::where('proveedor_id', $proveedor->id)->get();
        $disponibles = Producto::whereNull('proveedor_id')->get();
        return view('proveedores.productos', compact('proveedor', 'productos', 'disponibles'));
    }

    public function asignarProductos(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'productos' => 'required|array',
            'productos.*' => 'exists:productos,id'
        ]);

        Producto::whereIn('id', $request->productos)
            ->update(['proveedor_id' => $proveedor->id]);

        return redirect()->route('proveedores.productos', $proveedor)
            ->with('success', 'Productos asignados exitosamente');
    }
}