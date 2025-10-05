<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\Gasto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mes = (int) $request->get('mes', Carbon::now()->month);
        $anio = (int) $request->get('anio', Carbon::now()->year);

        $ingresos = Ingreso::whereYear('fecha', $anio)->whereMonth('fecha', $mes)->get();
        $gastos = Gasto::whereYear('fecha', $anio)->whereMonth('fecha', $mes)->get();

        $totalIngresos = $ingresos->sum('monto');
        $gastosFijos = $gastos->where('tipo', 'fijo')->sum('monto');
        $gastosDinamicos = $gastos->where('tipo', 'dinamico')->sum('monto');
        $totalGastos = $gastosFijos + $gastosDinamicos;
        $saldo = $totalIngresos - $totalGastos;

        $porcentajeFijos = $totalIngresos > 0 ? ($gastosFijos / $totalIngresos) * 100 : 0;
        $porcentajeDinamicos = $totalIngresos > 0 ? ($gastosDinamicos / $totalIngresos) * 100 : 0;

        // Conclusiones
        if ($saldo < 0) {
            $conclusion = 'No hay capacidad de ahorro ni inversión. Los gastos superan los ingresos.';
            $claseConclusion = 'danger';
        } elseif ($saldo == 0) {
            $conclusion = 'No hay saldo disponible para ahorrar o invertir.';
            $claseConclusion = 'warning';
        } else {
            $conclusion = '¡Hay capacidad para ahorrar o invertir!';
            $claseConclusion = 'success';
        }

        // Gráfica: categorías
        $categorias = $gastos->where('tipo', 'dinamico')->groupBy('categoria')->map->sum('monto');

        return view('dashboard.index', compact(
            'mes', 'anio', 'totalIngresos', 'gastosFijos', 'gastosDinamicos',
            'saldo', 'porcentajeFijos', 'porcentajeDinamicos',
            'conclusion', 'claseConclusion', 'categorias'
        ));
    }
}