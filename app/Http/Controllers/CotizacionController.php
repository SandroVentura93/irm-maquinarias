<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    // Placeholder: redirect to ventas index for now (can be implemented later)
    public function index() { return redirect()->route('ventas.index'); }
    public function create() { return redirect()->route('ventas.create'); }
    public function store(Request $r) { return redirect()->route('ventas.index'); }
    public function show($id) { return redirect()->route('ventas.show', $id); }
    public function edit($id) { return redirect()->route('ventas.show', $id); }
    public function update(Request $r, $id) { return redirect()->route('ventas.show', $id); }
    public function destroy($id) { return redirect()->route('ventas.index'); }
}
