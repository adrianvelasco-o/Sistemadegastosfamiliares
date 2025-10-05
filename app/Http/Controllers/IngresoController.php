<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
    public function crear()
    {
        return view('ingresos.crear');
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:fijo,adicional',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
        ]);

        Ingreso::create($request->all());
        return redirect()->route('dashboard')->with('success', 'Ingreso registrado correctamente.');
    }
}