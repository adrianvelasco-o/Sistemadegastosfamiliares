@extends('layouts.app')
@section('title', 'Registrar Ingreso')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Registrar Nuevo Ingreso</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('ingreso.guardar') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tipo de ingreso</label>
                        <select name="tipo" class="form-select" required>
                            <option value="">Seleccionar...</option>
                            <option value="fijo">Fijo (salario, pensión)</option>
                            <option value="adicional">Adicional (bonos, extras)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto ($)</label>
                        <input type="number" name="monto" step="0.01" min="0.01" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Registrar Ingreso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection