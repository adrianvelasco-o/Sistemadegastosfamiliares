<?php

namespace Database\Seeders;

use App\Models\Ingreso;
use App\Models\Gasto;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatosFinanzasFamiliaresSeeder extends Seeder
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
        Gasto::create(['tipo' => 'fijo', 'categoria' => 'seguro', 'monto' => 50.00, 'fecha' => "$anio-$mes-10"]);

        // Gastos dinámicos detallados
        $gastosDinamicos = [
            ['categoria' => 'mercado', 'monto' => 120.00, 'dia' => 3],
            ['categoria' => 'mercado', 'monto' => 95.50, 'dia' => 10],
            ['categoria' => 'mercado', 'monto' => 110.00, 'dia' => 17],
            ['categoria' => 'mercado', 'monto' => 130.00, 'dia' => 24],
            ['categoria' => 'mercado', 'monto' => 105.00, 'dia' => 28],
            ['categoria' => 'restaurantes', 'monto' => 60.00, 'dia' => 5],
            ['categoria' => 'restaurantes', 'monto' => 75.00, 'dia' => 12],
            ['categoria' => 'restaurantes', 'monto' => 45.00, 'dia' => 19],
            ['categoria' => 'ropa', 'monto' => 200.00, 'dia' => 8],
            ['categoria' => 'entretenimiento', 'monto' => 35.00, 'dia' => 14],
            ['categoria' => 'entretenimiento', 'monto' => 50.00, 'dia' => 21],
            ['categoria' => 'imprevistos', 'monto' => 150.00, 'dia' => 2],
            ['categoria' => 'transporte', 'monto' => 30.00, 'dia' => 6],
            ['categoria' => 'transporte', 'monto' => 25.00, 'dia' => 13],
            ['categoria' => 'transporte', 'monto' => 40.00, 'dia' => 20],
            ['categoria' => 'salud', 'monto' => 80.00, 'dia' => 11],
            ['categoria' => 'educacion', 'monto' => 120.00, 'dia' => 15],
            ['categoria' => 'regalos', 'monto' => 60.00, 'dia' => 22],
            ['categoria' => 'hogar', 'monto' => 90.00, 'dia' => 9],
            ['categoria' => 'tecnologia', 'monto' => 300.00, 'dia' => 16],
        ];

        foreach ($gastosDinamicos as $gasto) {
            Gasto::create([
                'tipo' => 'dinamico',
                'categoria' => $gasto['categoria'],
                'monto' => $gasto['monto'],
                'fecha' => "$anio-$mes-{$gasto['dia']}"
            ]);
        }
    }
}