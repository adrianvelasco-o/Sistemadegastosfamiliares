@extends('layouts.app')
@section('title', '📋 Registro de Ingresos y Gastos')

@push('styles')
<style>
    .registro-ingreso { border-left: 4px solid #28a745; }
    .registro-gasto { border-left: 4px solid #dc3545; }
    .badge-ingreso { background-color: #d4edda; color: #155724; }
    .badge-gasto { background-color: #f8d7da; color: #721c24; }
    .filtro-container { background-color: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">
            <i class="fas fa-list me-2"></i>Registro de Ingresos y Gastos
        </h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
        </a>
    </div>

    <!-- Filtros -->
    <div class="filtro-container">
        <form method="GET" class="row g-3">
            <div class="col-md-2">
                <label class="form-label">Mes</label>
                <select name="mes" class="form-select form-select-sm">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->locale('es')->isoFormat('MMMM') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Año</label>
                <input type="number" name="anio" class="form-control form-control-sm" value="{{ $anio }}" min="2020" max="{{ date('Y') + 1 }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select form-select-sm">
                    <option value="todos" {{ $tipoFiltro == 'todos' ? 'selected' : '' }}>Todos</option>
                    <option value="ingresos" {{ $tipoFiltro == 'ingresos' ? 'selected' : '' }}>Solo Ingresos</option>
                    <option value="gastos" {{ $tipoFiltro == 'gastos' ? 'selected' : '' }}>Solo Gastos</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Categoría (gastos)</label>
                <input type="text" name="categoria" class="form-control form-control-sm" value="{{ $categoriaFiltro }}" placeholder="Buscar categoría...">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-sm me-2">
                    <i class="fas fa-filter me-1"></i> Filtrar
                </button>
                <a href="{{ route('registros.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times me-1"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Resumen -->
    <div class="row mb-4">
        <div class="col-md-4">z
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Ingresos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalIngresos, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Gastos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalGastos, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-{{ $saldo >= 0 ? 'primary' : 'warning' }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $saldo >= 0 ? 'primary' : 'warning' }} text-uppercase mb-1">Saldo</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($saldo, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-{{ $saldo >= 0 ? 'primary' : 'warning' }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de registros -->
    @if($registros->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle fa-2x mb-3"></i>
            <h5>No hay registros para los filtros seleccionados</h5>
            <p>Intenta ajustar los filtros o registrar nuevos ingresos/gastos.</p>
        </div>
    @else
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Registros del mes ({{ $registros->count() }} encontrados)
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Detalle</th>
                                <th>Categoría</th>
                                <th class="text-end">Monto ($)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registros as $registro)
                                <tr class="{{ $registro['tipo'] === 'ingreso' ? 'registro-ingreso' : 'registro-gasto' }}">
                                    <td>{{ \Carbon\Carbon::parse($registro['fecha'])->format('d/m/Y') }}</td>
                                    <td>
                                        @if($registro['tipo'] === 'ingreso')
                                            <span class="badge badge-ingreso">Ingreso</span>
                                        @else
                                            <span class="badge badge-gasto">Gasto</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($registro['tipo'] === 'ingreso')
                                            {{ ucfirst($registro['tipo_detalle']) }}
                                        @else
                                            {{ ucfirst($registro['tipo_detalle']) }}
                                        @endif
                                    </td>
                                    <td>{{ $registro['categoria'] }}</td>
                                    <td class="text-end fw-bold">
                                        @if($registro['tipo'] === 'ingreso')
                                            <span class="text-success">+ ${{ number_format($registro['monto'], 2) }}</span>
                                        @else
                                            <span class="text-danger">- ${{ number_format($registro['monto'], 2) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection