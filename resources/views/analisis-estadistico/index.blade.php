@extends('layouts.app')
@section('title', 'Análisis Estadístico de Gastos Dinámicos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📊 Análisis Estadístico de Gastos Dinámicos</h2>
    <form method="GET" class="d-flex gap-2">
        <select name="mes" class="form-select" style="width: 140px;">
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ request('mes', date('n')) == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->locale('es')->isoFormat('MMMM') }}
                </option>
            @endforeach
        </select>
        <input type="number" name="anio" value="{{ request('anio', date('Y')) }}" class="form-control" style="width: 90px;">
        <button type="submit" class="btn btn-outline-primary btn-sm">Actualizar</button>
    </form>
</div>

@if(!$tieneDatos)
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No hay gastos dinámicos registrados en este mes.
    </div>
    <a href="{{ route('gasto.crear') }}" class="btn btn-primary">Registrar un gasto dinámico</a>
@else
    <!-- DATOS NO AGRUPADOS -->
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-list me-2"></i> Datos No Agrupados (Valores Individuales)
        </div>
        <div class="card-body">
            <p><strong>Valores:</strong> {{ implode(', ', array_map(fn($v) => '$'.number_format($v,2), $estadisticasNoAgrupadas['valores'])) }}</p>
            <div class="row text-center">
                @foreach(['media', 'mediana', 'moda', 'rango', 'varianza', 'desviacionEstandar'] as $metrica)
                    <div class="col-md-2 mb-3">
                        <div class="small text-muted">{{ ucfirst(str_replace('desviacionEstandar', 'Desv. Est.', $metrica)) }}</div>
                        <div class="fw-bold text-primary">
                            @if($metrica == 'moda' && !is_numeric($estadisticasNoAgrupadas[$metrica]))
                                {{ $estadisticasNoAgrupadas[$metrica] }}
                            @else
                                ${{ $estadisticasNoAgrupadas[$metrica] }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row mt-3">
                <div class="col-md-8">
                    <canvas id="barrasNoAgrupado" height="80"></canvas>
                </div>
                <div class="col-md-4">
                    <canvas id="circularNoAgrupado" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- DATOS AGRUPADOS -->
    <div class="card border-success">
        <div class="card-header bg-success text-white">
            <i class="fas fa-th-large me-2"></i> Datos Agrupados (Intervalos)
        </div>
        <div class="card-body">
            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Intervalo ($)</th>
                            <th>Marca de Clase ($)</th>
                            <th>Frecuencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estadisticasAgrupadas['clases'] as $clase)
                            <tr>
                                <td>{{ number_format($clase['li'], 2) }} – {{ number_format($clase['ls'], 2) }}</td>
                                <td>{{ number_format($clase['marca'], 2) }}</td>
                                <td>{{ $clase['f'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row text-center mb-3">
                @foreach(['media', 'mediana', 'moda', 'rango', 'varianza', 'desviacionEstandar'] as $metrica)
                    <div class="col-md-2 mb-3">
                        <div class="small text-muted">{{ ucfirst(str_replace('desviacionEstandar', 'Desv. Est.', $metrica)) }}</div>
                        <div class="fw-bold text-success">${{ $estadisticasAgrupadas[$metrica] }}</div>
                    </div>
                @endforeach
            </div>
            <canvas id="histograma" height="80"></canvas>
        </div>
    </div>
@endif

@push('scripts')
@if($tieneDatos)
<script>
    // No agrupados
    const valores = @json($gastos->pluck('monto'));
    new Chart(document.getElementById('barrasNoAgrupado'), {
        type: 'bar',
         { labels: valores.map((_,i)=>`#${i+1}`), datasets: [{ label:'Monto', data:valores, backgroundColor:'#4e73df' }] },
        options: { responsive:true, plugins:{legend:{display:false}} }
    });

    // Circular (moda visual)
    const freq = {};
    valores.forEach(v => { const k = v.toFixed(2); freq[k] = (freq[k]||0)+1; });
    if (Object.keys(freq).length <= 8) {
        new Chart(document.getElementById('circularNoAgrupado'), {
            type: 'pie',
             { labels: Object.keys(freq), datasets: [{ data:Object.values(freq), backgroundColor:['#FF6384','#36A2EB','#FFCE56'] }] }
        });
    }

    // Histograma
    const clases = @json($estadisticasAgrupadas['clases']);
    new Chart(document.getElementById('histograma'), {
        type: 'bar',
         { 
            labels: clases.map(c=>`$${c.li.toFixed(0)}–${c.ls.toFixed(0)}`),
            datasets: [{ label:'Frecuencia', data:clases.map(c=>c.f), backgroundColor:'#1cc88a' }]
        },
        options: { responsive:true, scales:{y:{ticks:{stepSize:1}}} }
    });
</script>
@endif
@endpush
@endsection 