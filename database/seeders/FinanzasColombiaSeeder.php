<?php

namespace Database\Seeders;

use App\Models\Ingreso;
use App\Models\Gasto;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FinanzasColombiaSeeder extends Seeder
{
    public function run()
    {
        // php artisan db:seed --class=FinanzasColombiaSeeder

        // Limpiar datos existentes
        Ingreso::truncate();
        Gasto::truncate();

        $salarioMinimo = 1423500;
        $anio = 2025;

        // Datos por mes
        $datosMensuales = [
            1 => [ // Enero
                'ingresos_adicionales' => 0,
                'gastos_fijos' => [
                    'arriendo' => 650000,
                    'servicios' => 180000,
                    'transporte_fijo' => 150000,
                    'seguros' => 80000,
                    'educacion' => 200000,
                ],
                'gastos_dinamicos' => [
                    ['mercado', 380000, 5],
                    ['mercado', 420000, 20],
                    ['restaurantes', 60000, 12],
                    ['ropa', 150000, 8],
                    ['imprevistos', 120000, 3], // Año nuevo, imprevistos
                    ['salud', 90000, 15],
                ]
            ],
            2 => [ // Febrero
                'ingresos_adicionales' => 0,
                'gastos_fijos' => [
                    'arriendo' => 650000,
                    'servicios' => 160000, // Menos luz
                    'transporte_fijo' => 150000,
                    'seguros' => 80000,
                    'educacion' => 200000,
                ],
                'gastos_dinamicos' => [
                    ['mercado', 350000, 6],
                    ['mercado', 390000, 22],
                    ['restaurantes', 80000, 14], // Día del amor
                    ['regalos', 120000, 13], // San Valentín
                    ['imprevistos', 80000, 10],
                    ['salud', 70000, 18],
                ]
            ],
            3 => [ // Marzo
                'ingresos_adicionales' => 0,
                'gastos_fijos' => [
                    'arriendo' => 650000,
                    'servicios' => 190000, // Más agua
                    'transporte_fijo' => 150000,
                    'seguros' => 80000,
                    'educacion' => 200000,
                ],
                'gastos_dinamicos' => [
                    ['mercado', 400000, 4],
                    ['mercado', 430000, 21],
                    ['entretenimiento', 100000, 11],
                    ['hogar', 120000, 9],
                    ['imprevistos', 90000, 16],
                    ['salud', 80000, 25],
                ]
            ],
            4 => [ // Abril
                'ingresos_adicionales' => 0,
                'gastos_fijos' => [
                    'arriendo' => 650000,
                    'servicios' => 170000,
                    'transporte_fijo' => 150000,
                    'seguros' => 80000,
                    'educacion' => 200000,
                ],
                'gastos_dinamicos' => [
                    ['mercado', 450000, 2], // Semana Santa
                    ['mercado', 380000, 18],
                    ['restaurantes', 120000, 6], // Semana Santa
                    ['viajes', 300000, 5], // Semana Santa
                    ['imprevistos', 100000, 12],
                    ['salud', 75000, 20],
                ]
            ],
            5 => [ // Mayo
                'ingresos_adicionales' => 0,
                'gastos_fijos' => [
                    'arriendo' => 650000,
                    'servicios' => 180000,
                    'transporte_fijo' => 150000,
                    'seguros' => 80000,
                    'educacion' => 200000,
                ],
                'gastos_dinamicos' => [
                    ['mercado', 370000, 3],
                    ['mercado', 410000, 19],
                    ['ropa', 180000, 10], // Cambio de clima
                    ['imprevistos', 85000, 14],
                    ['salud', 85000, 22],
                    ['hogar', 90000, 8],
                ]
            ],
            6 => [ // Junio
                'ingresos_adicionales' => 300000, // Prima de mitad de año
                'gastos_fijos' => [
                    'arriendo' => 650000,
                    'servicios' => 190000,
                    'transporte_fijo' => 150000,
                    'seguros' => 80000,
                    'educacion' => 200000,
                ],
                'gastos_dinamicos' => [
                    ['mercado', 420000, 5],
                    ['mercado', 460000, 22],
                    ['tecnologia', 400000, 15], // Compras mitad de año
                    ['imprevistos', 110000, 8],
                    ['salud', 95000, 18],
                    ['entretenimiento', 120000, 25],
                ]
            ],
            7 => [ // Julio
                'ingresos_adicionales' => 0,
                'gastos_fijos' => [
                    'arriendo' => 650000,
                    'servicios' => 200000, // Más consumo
                    'transporte_fijo' => 150000,
                    'seguros' => 80000,
                    'educacion' => 200000,
                ],
                'gastos_dinamicos' => [
                    ['mercado', 390000, 4],
                    ['mercado', 430000, 20],
                    ['viajes', 250000, 12], // Vacaciones
                    ['imprevistos', 95000, 16],
                    ['salud', 80000, 24],
                    ['ropa', 160000, 9],
                ]
            ],
            8 => [ // Agosto
                'ingresos_adicionales' => 0,
                'gastos_fijos' => [
                    'arriendo' => 650000,
                    'servicios' => 180000,
                    'transporte_fijo' => 150000,
                    'seguros' => 80000,
                    'educacion' => 250000, // Útiles escolares
                ],
                'gastos_dinamicos' => [
                    ['mercado', 410000, 6],
                    ['mercado', 440000, 21],
                    ['hogar', 180000, 10], // Útiles escolares
                    ['imprevistos', 100000, 14],
                    ['salud', 90000, 19],
                    ['restaurantes', 70000, 26],
                ]
            ],
            9 => [ // Septiembre
                'ingresos_adicionales' => 0,
                'gastos_fijos' => [
                    'arriendo' => 650000,
                    'servicios' => 170000,
                    'transporte_fijo' => 150000,
                    'seguros' => 80000,
                    'educacion' => 200000,
                ],
                'gastos_dinamicos' => [
                    ['mercado', 380000, 5],
                    ['mercado', 420000, 20],
                    ['ropa', 140000, 12],
                    ['imprevistos', 85000, 17],
                    ['salud', 75000, 23],
                    ['entretenimiento', 90000, 8],
                ]
            ],
            10 => [ // Octubre
                'ingresos_adicionales' => 0,
                'gastos_fijos' => [
                    'arriendo' => 650000,
                    'servicios' => 190000,
                    'transporte_fijo' => 150000,
                    'seguros' => 80000,
                    'educacion' => 200000,
                ],
                'gastos_dinamicos' => [
                    ['mercado', 400000, 4],
                    ['mercado', 440000, 19],
                    ['hogar', 110000, 11],
                    ['imprevistos', 90000, 15],
                    ['salud', 85000, 22],
                    ['tecnologia', 200000, 28], // Black Friday anticipado
                ]
            ],
            11 => [ // Noviembre
                'ingresos_adicionales' => 0,
                'gastos_fijos' => [
                    'arriendo' => 650000,
                    'servicios' => 200000,
                    'transporte_fijo' => 150000,
                    'seguros' => 80000,
                    'educacion' => 200000,
                ],
                'gastos_dinamicos' => [
                    ['mercado', 430000, 3],
                    ['mercado', 470000, 20],
                    ['ropa', 220000, 10], // Preparación diciembre
                    ['imprevistos', 105000, 14],
                    ['salud', 95000, 21],
                    ['hogar', 150000, 25],
                ]
            ],
            12 => [ // Diciembre
                'ingresos_adicionales' => 500000, // Prima de navidad + aguinaldo
                'gastos_fijos' => [
                    'arriendo' => 650000,
                    'servicios' => 220000, // Más consumo
                    'transporte_fijo' => 150000,
                    'seguros' => 80000,
                    'educacion' => 200000,
                ],
                'gastos_dinamicos' => [
                    ['mercado', 550000, 5], // Navidad
                    ['mercado', 600000, 22], // Año nuevo
                    ['regalos', 300000, 15], // Navidad
                    ['restaurantes', 200000, 24], // Navidad
                    ['viajes', 400000, 26], // Fin de año
                    ['ropa', 250000, 10], // Ropa nueva año
                    ['imprevistos', 150000, 18],
                    ['salud', 120000, 28],
                ]
            ]
        ];

        foreach ($datosMensuales as $mes => $datos) {
            // Registrar ingresos
            Ingreso::create([
                'tipo' => 'fijo',
                'monto' => $salarioMinimo,
                'fecha' => Carbon::create($anio, $mes, 1)
            ]);

            if ($datos['ingresos_adicionales'] > 0) {
                Ingreso::create([
                    'tipo' => 'adicional',
                    'monto' => $datos['ingresos_adicionales'],
                    'fecha' => Carbon::create($anio, $mes, 15)
                ]);
            }

            // Registrar gastos fijos
            foreach ($datos['gastos_fijos'] as $categoria => $monto) {
                Gasto::create([
                    'tipo' => 'fijo',
                    'categoria' => $categoria,
                    'monto' => $monto,
                    'fecha' => Carbon::create($anio, $mes, 1)
                ]);
            }

            // Registrar gastos dinámicos
            foreach ($datos['gastos_dinamicos'] as $gasto) {
                [$categoria, $monto, $dia] = $gasto;
                Gasto::create([
                    'tipo' => 'dinamico',
                    'categoria' => $categoria,
                    'monto' => $monto,
                    'fecha' => Carbon::create($anio, $mes, $dia)
                ]);
            }
        }
    }
}