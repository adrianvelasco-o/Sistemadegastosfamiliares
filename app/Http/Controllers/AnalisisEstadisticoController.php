<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalisisEstadisticoController extends Controller
{
    public function index(Request $request)
    {
        $mes = (int) $request->get('mes', Carbon::now()->month);
        $anio = (int) $request->get('anio', Carbon::now()->year);

        $gastos = Gasto::where('tipo', 'dinamico')
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->orderBy('monto')
            ->get();

        $tieneDatos = $gastos->isNotEmpty();
        $estadisticasNoAgrupadas = null;
        $estadisticasAgrupadas = null;

        if ($tieneDatos) {
            $valores = $gastos->pluck('monto')->toArray();
            $estadisticasNoAgrupadas = $this->calcularNoAgrupado($valores);
            $estadisticasAgrupadas = $this->calcularAgrupado($valores, 5);
        }

        return view('analisis-estadistico.index', compact(
            'mes', 'anio', 'tieneDatos', 'gastos',
            'estadisticasNoAgrupadas', 'estadisticasAgrupadas'
        ));
    }

    private function calcularNoAgrupado(array $valores): array
    {
        sort($valores);
        $n = count($valores);
        $media = array_sum($valores) / $n;
        $mediana = $n % 2 === 1 ? $valores[intval($n / 2)] : ($valores[$n / 2 - 1] + $valores[$n / 2]) / 2;
        $frecuencias = array_count_values($valores);
        arsort($frecuencias);
        $frecMax = reset($frecuencias);
        $moda = $frecMax > 1 ? (float) key($frecuencias) : 'No hay moda';
        $rango = max($valores) - min($valores);
        $varianza = array_sum(array_map(fn($x) => ($x - $media) ** 2, $valores)) / $n;
        $desviacionEstandar = sqrt($varianza);

        return [
            'valores' => $valores,
            'media' => round($media, 2),
            'mediana' => round($mediana, 2),
            'moda' => is_numeric($moda) ? round($moda, 2) : $moda,
            'rango' => round($rango, 2),
            'varianza' => round($varianza, 2),
            'desviacionEstandar' => round($desviacionEstandar, 2),
        ];
    }

    private function calcularAgrupado(array $valores, int $k): array
    {
        $min = min($valores);
        $max = max($valores);
        $rango = $max - $min;

        if ($rango == 0) {
            $v = $min;
            return [
                'clases' => [['li' => $v, 'ls' => $v + 1, 'marca' => $v, 'f' => count($valores)]],
                'media' => $v, 'mediana' => $v, 'moda' => $v,
                'rango' => 0, 'varianza' => 0, 'desviacionEstandar' => 0,
            ];
        }

        $amplitud = ceil($rango / $k);
        $clases = [];
        for ($i = 0; $i < $k; $i++) {
            $li = $min + ($i * $amplitud);
            $ls = ($i === $k - 1) ? $max + 0.01 : $min + (($i + 1) * $amplitud);
            $clases[] = ['li' => $li, 'ls' => $ls, 'marca' => ($li + $ls) / 2, 'f' => 0];
        }

        foreach ($valores as $v) {
            foreach ($clases as &$c) {
                if ($v >= $c['li'] && $v < $c['ls']) {
                    $c['f']++;
                    break;
                }
            }
        }

        $n = array_sum(array_column($clases, 'f'));
        $media = array_sum(array_map(fn($c) => $c['marca'] * $c['f'], $clases)) / $n;

        $modal = collect($clases)->sortByDesc('f')->first();
        $idx = array_search($modal, $clases);
        $fAnt = $idx > 0 ? $clases[$idx - 1]['f'] : 0;
        $fPost = $idx < count($clases) - 1 ? $clases[$idx + 1]['f'] : 0;
        $d1 = $modal['f'] - $fAnt;
        $d2 = $modal['f'] - $fPost;
        $moda = ($d1 + $d2) > 0 ? $modal['li'] + ($d1 / ($d1 + $d2)) * $amplitud : $modal['marca'];

        $posMed = $n / 2;
        $fa = 0;
        $claseMed = null;
        foreach ($clases as $c) {
            $fa += $c['f'];
            if ($fa >= $posMed) {
                $claseMed = $c;
                break;
            }
        }

        if ($claseMed === null || $claseMed['f'] <= 0) {
            $claseMed = collect($clases)->firstWhere('f', '>', 0) ?? $clases[0];
            $faAnt = 0;
        } else {
            $faAnt = $fa - $claseMed['f'];
        }

        $mediana = ($claseMed['f'] > 0)
            ? $claseMed['li'] + (($posMed - $faAnt) / $claseMed['f']) * $amplitud
            : $claseMed['marca'];

        $varianza = array_sum(array_map(fn($c) => $c['f'] * ($c['marca'] - $media) ** 2, $clases)) / $n;

        return [
            'clases' => $clases,
            'media' => round($media, 2),
            'mediana' => round($mediana, 2),
            'moda' => round($moda, 2),
            'rango' => round($rango, 2),
            'varianza' => round($varianza, 2),
            'desviacionEstandar' => round(sqrt($varianza), 2),
        ];
    }
}
