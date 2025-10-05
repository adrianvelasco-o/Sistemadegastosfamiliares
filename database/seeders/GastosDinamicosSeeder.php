<?php

namespace Database\Seeders;

use App\Models\Gasto;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GastosDinamicosSeeder extends Seeder
{
    public function run()
    {
        // Limpiar gastos existentes (solo para desarrollo)
        Gasto::truncate();

        $mes = Carbon::now()->month;
        $anio = Carbon::now()->year;

        // Datos de ejemplo: gastos dinámicos variados (algunos repetidos)
        $datosEjemplo = [
            45.50, 120.00, 30.00, 85.75, 45.50, 200.00,
            60.25, 45.50, 90.00, 150.00, 75.00, 45.50,
            110.00, 55.00, 130.00
        ];

        foreach ($datosEjemplo as $index => $monto) {
            Gasto::create([
                'tipo' => 'dinamico',
                'categoria' => $this->categoriaAleatoria(),
                'monto' => $monto,
                'fecha' => Carbon::create($anio, $mes, rand(1, 28))
            ]);
        }

        // También agregamos algunos gastos fijos (para no interferir)
        Gasto::create([
            'tipo' => 'fijo',
            'categoria' => 'arriendo',
            'monto' => 800.00,
            'fecha' => Carbon::create($anio, $mes, 1)
        ]);
    }

    private function categoriaAleatoria()
    {
        $categorias = ['mercado', 'restaurantes', 'ropa', 'entretenimiento', 'imprevistos', 'transporte'];
        return $categorias[array_rand($categorias)];
    }
}