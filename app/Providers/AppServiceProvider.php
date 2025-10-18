<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Schema::defaultStringLength(191);
        // Share low-stock products globally for the alert modal (safe checks)
        try {
            $productosStockBajo = collect();
            $alertaStockEnabled = false;
            if (Schema::hasTable('productos')) {
                // check configuracion if exists
                if (Schema::hasTable('configuraciones')) {
                    $val = DB::table('configuraciones')->where('clave', 'alerta_stock')->value('valor');
                    $alertaStockEnabled = (bool) $val;
                } else {
                    // default to true if configuration not present
                    $alertaStockEnabled = true;
                }

                if ($alertaStockEnabled) {
                    $productosStockBajo = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')
                        ->where('activo', 1)->limit(10)->get();
                }
            }

            View::share('productosStockBajo', $productosStockBajo);
            View::share('alertaStockEnabled', $alertaStockEnabled);
        } catch (\Exception $e) {
            // swallow any errors to avoid breaking the app
            View::share('productosStockBajo', collect());
            View::share('alertaStockEnabled', false);
        }
    }
}
