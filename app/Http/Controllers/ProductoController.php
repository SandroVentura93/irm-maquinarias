<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::latest()->paginate(15);
        $categorias = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('categorias')) {
                $categorias = \App\Models\Categoria::all();
            }
        } catch (\Exception $e) {
            // ignore if DB not available
        }

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = null;
        $proveedores = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('categorias')) {
                $categorias = \App\Models\Categoria::all();
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('proveedores')) {
                $proveedores = \App\Models\Proveedor::all();
            }
        } catch (\Exception $e) {
            // ignore
        }

        return view('productos.create', compact('categorias', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|unique:productos,codigo',
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'nullable|numeric|min:0',
            // form sends precio_unitario, accept it
            'precio_unitario' => 'nullable|numeric|min:0',
            'stock_actual' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'marca' => 'nullable|string',
            'categoria' => 'nullable|string',
            'unidad_medida' => 'nullable|string',
            'ubicacion' => 'nullable|string',
            'activo' => 'sometimes|boolean'
        ]);

        // Map precio_unitario -> precio_venta if provided
        if ($request->filled('precio_unitario')) {
            $data['precio_venta'] = $request->input('precio_unitario');
        }

        // Map categoria_id to categoria description if categorias table exists
        if ($request->filled('categoria_id')) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('categorias')) {
                    $cat = \App\Models\Categoria::find($request->input('categoria_id'));
                    $data['categoria'] = $cat ? ($cat->descripcion ?? $cat->nombre) : null;
                } else {
                    $data['categoria'] = $request->input('categoria_id');
                }
            } catch (\Exception $e) {
                $data['categoria'] = $request->input('categoria_id');
            }
        }

        $data['activo'] = $request->has('activo') ? (bool) $request->activo : true;
        Producto::create($data);
        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $data = $request->validate([
            'codigo' => 'required|string|unique:productos,codigo,' . $producto->id,
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'marca' => 'nullable|string',
            'categoria' => 'nullable|string',
            'unidad_medida' => 'nullable|string',
            'ubicacion' => 'nullable|string',
            'activo' => 'sometimes|boolean'
        ]);

        $producto->update($data);
        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado');
    }

    public function buscar(Request $request)
    {
        $q = $request->get('q');
        $productos = Producto::where('nombre', 'LIKE', "%{$q}%")
            ->orWhere('codigo', 'LIKE', "%{$q}%")
            ->limit(20)->get();
        return response()->json($productos);
    }

    public function stockBajo()
    {
        $productos = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')->get();
        return view('productos.stock_bajo', compact('productos'));
    }

    public function actualizarStock(Request $request)
    {
        $request->validate([ 'producto_id' => 'required|exists:productos,id', 'cantidad' => 'required|integer' ]);
        $p = Producto::findOrFail($request->producto_id);
        $p->stock_actual = $request->cantidad;
        $p->save();
        return back()->with('success', 'Stock actualizado');
    }

    public function pedidos()
    {
        $productos = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')->get();
        return view('pedidos.index', compact('productos'));
    }

    public function generarPedido(Request $request)
    {
        // Minimal placeholder: mark as TODO or create a simple PDF in the future
        return back()->with('success', 'Pedido generado (placeholder)');
    }
}
