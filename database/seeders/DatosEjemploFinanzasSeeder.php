<?php

namespace Database\Seeders;

use App\Models\Ingreso;
use App\Models\Gasto;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatosEjemploFinanzasSeeder extends Seeder
{
    public function run()
    {
        Ingreso::truncate();
        Gasto::truncate();

        $mes = Carbon::now()->month;
        $anio = Carbon::now()->year;

        // Ingresos
        Ingreso::create(['tipo' => 'fijo', 'monto' => 2500.00, 'fecha' => "$anio-$mes-01"]);
        Ingreso::create(['tipo' => 'adicional', 'monto' => 300.00, 'fecha' => "$anio-$mes-15"]);

        // Gastos fijos
        Gasto::create(['tipo' => 'fijo', 'categoria' => 'arriendo', 'monto' => 800.00, 'fecha' => "$anio-$mes-01"]);
        Gasto::create(['tipo' => 'fijo', 'categoria' => 'servicios', 'monto' => 150.00, 'fecha' => "$anio-$mes-05"]);
        Gasto::create(['tipo' => 'fijo', 'categoria' => 'transporte', 'monto' => 120.00, 'fecha' => "$anio-$mes-03"]);

        // Gastos dinámicos (para análisis estadístico)
        $gastosDinamicos = [45.50, 120.00, 30.00, 85.75, 45.50, 200.00, 60.25, 45.50, 90.00, 150.00];
        foreach ($gastosDinamicos as $monto) {
            Gasto::create([
                'tipo' => 'dinamico',
                'categoria' => collect(['mercado', 'restaurantes', 'ropa', 'entretenimiento'])->random(),
                'monto' => $monto,
                'fecha' => Carbon::create($anio, $mes, rand(1, 28))
            ]);
        }
    }
}