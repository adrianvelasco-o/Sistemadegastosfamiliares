@extends('layouts.app')
@section('title', 'Dashboard Financiero')

@section('content')
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ingresos Totales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalIngresos, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Gastos Totales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($gastosFijos + $gastosDinamicos, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Saldo Disponible</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($saldo, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-wallet fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Capacidad de Ahorro</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $saldo >= 0 ? 'Sí' : 'No' }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-piggy-bank fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-{{ $claseConclusion }} mb-4">
    <i class="fas fa-{{ $claseConclusion == 'success' ? 'check-circle' : ($claseConclusion == 'warning' ? 'exclamation-triangle' : 'times-circle') }} me-2"></i>
    <strong>Conclusión:</strong> {{ $conclusion }}
</div>

<div class="row">
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Distribución de Gastos por Categoría</h6>
            </div>
            <div class="card-body">
                <canvas id="graficaCategorias" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Destino de Ingresos</h6>
            </div>
            <div class="card-body">
                <canvas id="graficaPorcentajes" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    // Gráfica circular: categorías
    const ctx1 = document.getElementById('graficaCategorias').getContext('2d');
    new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: @json(array_keys($categorias->toArray())),
            datasets: [{
                data: @json(array_values($categorias->toArray())),
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'right' }
            }
        }
    });

    // Gráfica donut: porcentajes
    const ctx2 = document.getElementById('graficaPorcentajes').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Gastos Fijos', 'Gastos Dinámicos', 'Ahorro/Inversión'],
            datasets: [{
                data: [
                    {{ $porcentajeFijos }},
                    {{ $porcentajeDinamicos }},
                    {{ max(0, 100 - $porcentajeFijos - $porcentajeDinamicos) }}
                ],
                backgroundColor: ['#FF6384', '#36A2EB', '#4BC0C0']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush