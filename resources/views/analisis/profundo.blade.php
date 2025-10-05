@extends('layouts.app')
@section('title', '🔍 Análisis de Finanzas Familiares')

@push('styles')
<style>
    .metric-card { border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.2s; }
    .metric-card:hover { transform: translateY(-3px); }
    .insight-badge { font-size: 0.85rem; padding: 0.25rem 0.5rem; border-radius: 50px; }
</style>
@endpush

@section('content')

<div class="container-fluid py-4">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary"><i class="fas fa-chart-line me-2"></i>Análisis de datos</h2>
            <p class="text-muted">Mes: {{ \Carbon\Carbon::create()->month($mes)->locale('es')->isoFormat('MMMM YYYY') }}</p>
        </div>
        <form method="GET" class="d-flex gap-2">
            <select name="mes" class="form-select" style="width: 140px;">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->locale('es')->isoFormat('MMMM') }}
                    </option>
                @endforeach
            </select>
            <input type="number" name="anio" value="{{ $anio }}" class="form-control" style="width: 90px;">
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-sync"></i> Actualizar
            </button>
        </form>
    </div>

    @if(!$tieneDatos)
        <div class="alert alert-info d-flex align-items-start">
            <i class="fas fa-info-circle fa-2x me-3 mt-1"></i>
            <div>
                <h5>No hay datos suficientes para el análisis</h5>
                <p>Registra al menos 5 gastos dinámicos para activar el análisis detallado.</p>
            </div>
        </div>
        @return
    @endif

    <!-- INSIGHTS PRINCIPALES -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Categoría Principal</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $categoriaPrincipal }}
                            </div>
                            <div class="text-xs text-muted">{{ number_format($porcentajeCategoriaPrincipal, 1) }}% del total</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Variabilidad</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cv }}%</div>
                            <div class="text-xs text-muted">{{ $cv < 30 ? 'Baja (estable)' : ($cv < 60 ? 'Moderada' : 'Alta (impredecible)') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Gasto Atípico</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($gastoAtipico, 2) }}</div>
                            <div class="text-xs text-muted">Máximo del mes</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GRÁFICAS PRINCIPALES -->
    <div class="row mb-4">
        <!-- Gráfica 1: Distribución por categoría -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribución de Gastos por Categoría</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="graficaCategorias"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Top 3 categorías
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-gray"></i> Otras
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfica 2: Evolución semanal -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Gastos por Día de la Semana</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="graficaSemanal"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ANÁLISIS NO AGRUPADO -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Datos No Agrupados (Gastos Individuales)</h6>
            <span class="badge bg-primary">{{ count($gastosDinamicos) }} registros</span>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Tabla de gastos -->
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Categoría</th>
                                    <th>Monto</th>
                                    <th>Día</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gastosDinamicos->take(10) as $gasto)
                                    <tr>
                                        <td>{{ $gasto->fecha->format('d/m') }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $gasto->categoria }}</span>
                                        </td>
                                        <td class="fw-bold">${{ number_format($gasto->monto, 2) }}</td>
                                        <td>{{ $gasto->fecha->locale('es')->isoFormat('ddd') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($gastosDinamicos->count() > 10)
                            <small class="text-muted">Mostrando los primeros 10 de {{ $gastosDinamicos->count() }} registros</small>
                        @endif
                    </div>
                </div>

                <!-- Métricas estadísticas -->
                <div class="col-md-6">
                    <div class="row g-3">
                        @php
                            $metricas = [
                                ['label' => 'Media', 'value' => '$' . $estadisticas['media'], 'color' => 'primary'],
                                ['label' => 'Mediana', 'value' => '$' . $estadisticas['mediana'], 'color' => 'success'],
                                ['label' => 'Rango', 'value' => '$' . $estadisticas['rango'], 'color' => 'warning'],
                                ['label' => 'Desv. Est.', 'value' => '$' . $estadisticas['desviacion'], 'color' => 'info'],
                                ['label' => 'CV (%)', 'value' => $estadisticas['cv'] . '%', 'color' => 'danger'],
                                ['label' => 'Máximo', 'value' => '$' . $estadisticas['maximo'], 'color' => 'dark'],
                            ];
                        @endphp
                        @foreach($metricas as $metrica)
                            <div class="col-md-6">
                                <div class="metric-card p-3 bg-light border-start border-{{ $metrica['color'] }}">
                                    <div class="small text-{{ $metrica['color'] }}">{{ $metrica['label'] }}</div>
                                    <div class="h6 mb-0 fw-bold">{{ $metrica['value'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ANÁLISIS AGRUPADO POR TERCIO DE MES -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Evolución de Gastos por Tercio del Mes</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="graficaTercios"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Insight:</strong> 
                            @if($tercioTres < $tercioUno && $tercioTres < $tercioDos)
                                El control de gastos mejora hacia el final del mes.
                            @elseif($tercioUno > $tercioDos && $tercioUno > $tercioTres)
                                La mayor parte del gasto ocurre en los primeros días del mes.
                            @else
                                Los gastos están distribuidos de manera equilibrada.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfica circular: categorías
    const ctx1 = document.getElementById('graficaCategorias');
    if (ctx1) {
        const labels = @json(array_keys($gastosPorCategoria->toArray()));
        const data = @json(array_values($gastosPorCategoria->toArray()));

        if (labels.length > 0) {
            new Chart(ctx1.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        } else {
            ctx1.closest('.card-body').innerHTML = 
                '<div class="alert alert-warning text-center">No hay gastos dinámicos para mostrar</div>';
        }
    }

    // Gráfica semanal
const ctx2 = document.getElementById('graficaSemanal');
if (ctx2) {
    const diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
    const datos = @json($gastosPorDia);

    new Chart(ctx2.getContext('2d'), {
        type: 'bar',
        data: {
            labels: diasSemana,
            datasets: [{
                label: 'Total gastado',
                data: datos,
                backgroundColor: '#36b9cc',
                hoverBackgroundColor: '#2c9faf'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            },
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
}

    // Gráfica por tercios
    const ctx3 = document.getElementById('graficaTercios');
    if (ctx3) {
        new Chart(ctx3.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Primer tercio (1-10)', 'Segundo tercio (11-20)', 'Tercer tercio (21-30)'],
                datasets: [{
                    data: @json([$tercioUno, $tercioDos, $tercioTres]),
                    backgroundColor: 'rgba(54, 185, 204, 0.2)',
                    borderColor: '#36b9cc',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
@endpush

@endsection