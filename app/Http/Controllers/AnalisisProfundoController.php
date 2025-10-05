<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalisisProfundoController extends Controller
{
    public function index(Request $request)
    {
        $mes = (int) $request->get('mes', Carbon::now()->month);
        $anio = (int) $request->get('anio', Carbon::now()->year);

        $gastosDinamicos = Gasto::where('tipo', 'dinamico')
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->orderBy('fecha')
            ->get();

        $tieneDatos = $gastosDinamicos->count() >= 5;

        if (!$tieneDatos) {
            return view('analisis.profundo', compact('mes', 'anio', 'tieneDatos'));
        }

        // Estadísticas no agrupadas
        $valores = $gastosDinamicos->pluck('monto')->toArray();
        $estadisticas = $this->calcularEstadisticas($valores);

        // Por categoría
        $gastosPorCategoria = $gastosDinamicos->groupBy('categoria')
            ->map(fn($items) => $items->sum('monto'))
            ->sortDesc();
        $categoriaPrincipal = $gastosPorCategoria->keys()->first();
        $totalGastos = array_sum($gastosPorCategoria->toArray());
        $porcentajeCategoriaPrincipal = ($gastosPorCategoria->first() / $totalGastos) * 100;

        // Por día de la semana
        $gastosPorDia = array_fill(0, 7, 0);
        foreach ($gastosDinamicos as $gasto) {
            $dia = $gasto->fecha->dayOfWeek;
            $indice = $dia == 0 ? 6 : $dia - 1;
            $gastosPorDia[$indice] += $gasto->monto;
        }

        // Por tercio del mes
        $tercioUno = $gastosDinamicos->filter(fn($g) => $g->fecha->day <= 10)->sum('monto') ?: 0;
        $tercioDos = $gastosDinamicos->filter(fn($g) => $g->fecha->day > 10 && $g->fecha->day <= 20)->sum('monto') ?: 0;
        $tercioTres = $gastosDinamicos->filter(fn($g) => $g->fecha->day > 20)->sum('monto') ?: 0;

        $gastoAtipico = max($valores);
        $cv = $estadisticas['cv'];

        return view('analisis.profundo', compact(
            'mes', 'anio', 'tieneDatos', 'gastosDinamicos',
            'estadisticas', 'gastosPorCategoria', 'categoriaPrincipal',
            'porcentajeCategoriaPrincipal', 'gastosPorDia',
            'tercioUno', 'tercioDos', 'tercioTres', 'gastoAtipico', 'cv'
        ));
    }

    private function calcularEstadisticas(array $valores): array
    {
        sort($valores);
        $n = count($valores);
        $media = array_sum($valores) / $n;
        $mediana = $n % 2 === 1 ? $valores[intval($n / 2)] : ($valores[$n / 2 - 1] + $valores[$n / 2]) / 2;
        $maximo = max($valores);
        $rango = $maximo - min($valores);
        $varianza = array_sum(array_map(fn($x) => ($x - $media) ** 2, $valores)) / $n;
        $desviacion = sqrt($varianza);
        $cv = round(($desviacion / $media) * 100, 1);

        return compact('media', 'mediana', 'maximo', 'rango', 'desviacion', 'cv');
    }
}