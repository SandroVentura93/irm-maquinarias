<?php
namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Mostrar listado de productos
     */
    public function index()
    {
        $productos = Producto::orderBy('id', 'desc')->paginate(15);
        return view('productos.index', compact('productos'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categorias = \App\Models\Categoria::all();
        $proveedores = \App\Models\Proveedor::all();
        return view('productos.create', compact('categorias', 'proveedores'));
    }

    /**
     * Guardar nuevo producto
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|unique:productos,codigo',
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'marca' => 'nullable|string',
            'unidad_medida' => 'nullable|string',
            'ubicacion' => 'nullable|string',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'nullable|numeric|min:0',
            'stock_actual' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'categoria_id' => 'nullable|integer|exists:categorias,id',
            'activo' => 'sometimes|boolean',
        ]);
        $data['activo'] = $request->has('activo') ? (bool) $request->activo : true;
        try {
            Producto::create($data);
            return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al guardar producto', ['data' => $data, 'exception' => $e]);
            return back()->withInput()->withErrors(['error' => 'No se pudo guardar el producto: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar detalle de producto
     */
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = \App\Models\Categoria::all();
        $proveedores = \App\Models\Proveedor::all();
        return view('productos.edit', compact('producto', 'categorias', 'proveedores'));
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $data = $request->validate([
            'codigo' => 'required|string|unique:productos,codigo,' . $id,
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'marca' => 'nullable|string',
            'unidad_medida' => 'nullable|string',
            'ubicacion' => 'nullable|string',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'nullable|numeric|min:0',
            'stock_actual' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'categoria_id' => 'nullable|integer|exists:categorias,id',
            'activo' => 'sometimes|boolean',
        ]);
        $data['activo'] = $request->has('activo') ? (bool) $request->activo : true;
        $producto->update($data);
        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Eliminar producto
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente');
    }
}
