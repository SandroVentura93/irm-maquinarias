<?php

namespace App\Http\Controllers;

use App\Models\Moneda;
use App\Models\TipoCambio;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $configuraciones = Configuracion::all()->keyBy('clave');
        return view('configuracion.index', compact('configuraciones'));
    }

    public function actualizarGeneral(Request $request)
    {
        $request->validate([
            'razon_social' => 'required|string|max:255',
            'ruc' => 'required|string|size:11',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // Actualizar configuraciones básicas
            foreach ($request->except(['_token', 'logo']) as $clave => $valor) {
                Configuracion::establecer($clave, $valor);
            }

            // Manejar el logo si se proporciona uno nuevo
            if ($request->hasFile('logo')) {
                $logoAntiguo = Configuracion::obtener('logo');
                if ($logoAntiguo) {
                    Storage::delete('public/' . $logoAntiguo);
                }

                $ruta = $request->file('logo')->store('logos', 'public');
                Configuracion::establecer('logo', $ruta);
            }

            return redirect()->back()->with('success', 'Configuración actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la configuración.')
                ->withInput();
        }
    }

    public function actualizarDocumentos(Request $request)
    {
        $request->validate([
            'serie_boleta' => 'required|string|size:4',
            'serie_factura' => 'required|string|size:4',
            'igv' => 'required|numeric|between:0,1'
        ]);

        try {
            foreach ($request->except('_token') as $clave => $valor) {
                Configuracion::establecer($clave, $valor);
            }

            return redirect()->back()->with('success', 'Configuración de documentos actualizada.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la configuración de documentos.')
                ->withInput();
        }
    }

    public function actualizarInventario(Request $request)
    {
        $request->validate([
            'alerta_stock' => 'required|boolean',
            'alerta_stock_dias' => 'required|integer|min:1|max:90'
        ]);

        try {
            foreach ($request->except('_token') as $clave => $valor) {
                Configuracion::establecer($clave, $valor);
            }

            return redirect()->back()->with('success', 'Configuración de inventario actualizada.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la configuración de inventario.')
                ->withInput();
        }
    }

    public function moneda()
    {
        $monedas = Moneda::all();
        $tiposCambio = TipoCambio::orderBy('fecha', 'desc')->take(10)->get();
        $configuraciones = Configuracion::whereIn('clave', [
            'moneda_principal',
            'moneda_secundaria',
            'tipo_cambio',
            'ultima_actualizacion_tc'
        ])->get()->keyBy('clave');
        
        return view('configuracion.moneda', compact('monedas', 'tiposCambio', 'configuraciones'));
    }

    public function actualizarTipoCambio(Request $request)
    {
        $request->validate([
            'moneda_origen' => 'required|exists:monedas,id',
            'moneda_destino' => 'required|exists:monedas,id',
            'tipo_cambio' => 'required|numeric|min:0',
            'fecha' => 'required|date'
        ]);

        $tipoCambio = TipoCambio::create($request->all());

        // Actualizar configuración global del tipo de cambio
        Configuracion::establecer('tipo_cambio', $request->tipo_cambio);
        Configuracion::establecer('ultima_actualizacion_tc', now()->format('Y-m-d H:i:s'));

        return redirect()->route('configuracion.moneda')
            ->with('success', 'Tipo de cambio actualizado exitosamente');
    }

    public function backup()
    {
        try {
            // Crear backup de la base de datos
            $filename = 'backup-' . date('Y-m-d-His') . '.sql';
            $command = sprintf(
                'mysqldump -u%s -p%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                storage_path('app/backups/' . $filename)
            );

            exec($command);

            // Comprimir el archivo
            $zip = new \ZipArchive();
            $zipName = storage_path('app/backups/' . str_replace('.sql', '.zip', $filename));
            
            if ($zip->open($zipName, \ZipArchive::CREATE) === TRUE) {
                $zip->addFile(storage_path('app/backups/' . $filename), $filename);
                $zip->close();

                // Eliminar el archivo .sql
                unlink(storage_path('app/backups/' . $filename));

                return response()->download($zipName)->deleteFileAfterSend();
            }

            return redirect()->back()->with('error', 'Error al crear el archivo de respaldo.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el respaldo: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:zip'
        ]);

        try {
            $file = $request->file('backup_file');
            $zip = new \ZipArchive();
            
            if ($zip->open($file->path()) === TRUE) {
                $zip->extractTo(storage_path('app/temp/'));
                $zip->close();

                $sqlFile = glob(storage_path('app/temp/*.sql'))[0];
                
                $command = sprintf(
                    'mysql -u%s -p%s %s < %s',
                    config('database.connections.mysql.username'),
                    config('database.connections.mysql.password'),
                    config('database.connections.mysql.database'),
                    $sqlFile
                );

                exec($command);

                // Limpiar archivos temporales
                unlink($sqlFile);
                rmdir(storage_path('app/temp'));

                return redirect()->back()->with('success', 'Base de datos restaurada exitosamente.');
            }

            return redirect()->back()->with('error', 'Error al abrir el archivo de respaldo.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al restaurar la base de datos: ' . $e->getMessage());
        }
    }
}