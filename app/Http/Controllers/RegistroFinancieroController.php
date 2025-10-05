<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\Gasto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegistroFinancieroController extends Controller
{
    public function index(Request $request)
    {
        $mes = (int) $request->get('mes', Carbon::now()->month);
        $anio = (int) $request->get('anio', Carbon::now()->year);
        $tipoFiltro = $request->get('tipo', 'todos'); // 'ingresos', 'gastos', 'todos'
        $categoriaFiltro = $request->get('categoria', '');

        // Consultar ingresos
        $queryIngresos = Ingreso::whereYear('fecha', $anio)->whereMonth('fecha', $mes);
        $ingresos = $queryIngresos->orderBy('fecha', 'desc')->get();

        // Consultar gastos
        $queryGastos = Gasto::whereYear('fecha', $anio)->whereMonth('fecha', $mes);
        
        if ($categoriaFiltro) {
            $queryGastos->where('categoria', 'LIKE', "%{$categoriaFiltro}%");
        }
        
        $gastos = $queryGastos->orderBy('fecha', 'desc')->get();

        // Calcular totales
        $totalIngresos = $ingresos->sum('monto');
        $totalGastos = $gastos->sum('monto');
        $saldo = $totalIngresos - $totalGastos;

        // Obtener categorías únicas para el filtro
        $categorias = Gasto::whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->distinct()
            ->pluck('categoria')
            ->sort();

        // Combinar y ordenar todos los registros
        $registros = collect();
        
        if ($tipoFiltro === 'todos' || $tipoFiltro === 'ingresos') {
            $registros = $registros->merge(
                $ingresos->map(fn($ingreso) => [
                    'tipo' => 'ingreso',
                    'tipo_detalle' => $ingreso->tipo,
                    'categoria' => '-',
                    'monto' => $ingreso->monto,
                    'fecha' => $ingreso->fecha,
                    'id' => $ingreso->id,
                ])
            );
        }
        
        if ($tipoFiltro === 'todos' || $tipoFiltro === 'gastos') {
            $registros = $registros->merge(
                $gastos->map(fn($gasto) => [
                    'tipo' => 'gasto',
                    'tipo_detalle' => $gasto->tipo,
                    'categoria' => $gasto->categoria,
                    'monto' => $gasto->monto,
                    'fecha' => $gasto->fecha,
                    'id' => $gasto->id,
                ])
            );
        }

        // Ordenar por fecha (más reciente primero)
        $registros = $registros->sortByDesc('fecha')->values();

        return view('registros.index', compact(
            'registros', 'mes', 'anio', 'totalIngresos', 'totalGastos', 'saldo',
            'tipoFiltro', 'categoriaFiltro', 'categorias'
        ));
    }
}