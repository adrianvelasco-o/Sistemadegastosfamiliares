<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use Illuminate\Http\Request;

class GastoController extends Controller
{
    private function getCategoriasPorTipo($tipo)
    {
        $categorias = [
            'fijo' => [
                'arriendo' => 'Arriendo',
                'servicios' => 'Servicios (luz, agua, internet)',
                'transporte_fijo' => 'Transporte fijo',
                'seguros' => 'Seguros',
                'educacion' => 'Educación',
                'creditos' => 'Créditos/Prestamos'
            ],
            'dinamico' => [
                'mercado' => 'Mercado/Alimentos',
                'restaurantes' => 'Restaurantes',
                'ropa' => 'Ropa/Calzado',
                'entretenimiento' => 'Entretenimiento',
                'imprevistos' => 'Imprevistos',
                'salud' => 'Salud',
                'regalos' => 'Regalos/Celebraciones',
                'hogar' => 'Artículos para el hogar',
                'tecnologia' => 'Tecnología',
                'viajes' => 'Viajes'
            ]
        ];

        return $categorias[$tipo] ?? [];
    }

    public function crear()
    {
        return view('gastos.crear');
    }

    public function guardar(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'tipo' => 'required|in:fijo,dinamico',
            'categoria' => 'required|string',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
        ]);

        Gasto::create($request->only('tipo', 'categoria', 'monto', 'fecha'));
        
        return redirect()->route('dashboard')->with('success', 'Gasto registrado correctamente.');
    }

    // Para pasar las categorías a la vista vía AJAX o en la carga inicial
    public function getCategorias(Request $request)
    {
        $tipo = $request->get('tipo', 'dinamico');
        return response()->json($this->getCategoriasPorTipo($tipo));
    }
}