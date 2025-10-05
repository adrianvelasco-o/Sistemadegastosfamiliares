<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Finanzas Familiares')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-home me-2"></i>Finanzas Familiares
            </a>
            <!-- En el navbar -->
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('dashboard') }}">📊 Dashboard</a>
                <a class="nav-link" href="{{ route('analisis.profundo') }}">🔍 Análisis de datos</a>
                <a class="nav-link" href="{{ route('registros.index') }}">📋 Registros</a>
                <a class="nav-link" href="{{ route('ingreso.crear') }}">➕ Ingreso</a>
                <a class="nav-link" href="{{ route('gasto.crear') }}">➕ Gasto</a>
            </div>
        </div>
    </nav>

    <!-- Contenido -->
    <main class="py-4">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
    @php
function nombreCategoriaLegible($clave) {
    $nombres = [
        'arriendo' => 'Arriendo',
        'servicios' => 'Servicios',
        'transporte_fijo' => 'Transporte',
        'seguros' => 'Seguros',
        'educacion' => 'Educación',
        'creditos' => 'Créditos',
        'mercado' => 'Mercado',
        'restaurantes' => 'Restaurantes',
        'ropa' => 'Ropa',
        'entretenimiento' => 'Entretenimiento',
        'imprevistos' => 'Imprevistos',
        'salud' => 'Salud',
        'regalos' => 'Regalos',
        'hogar' => 'Hogar',
        'tecnologia' => 'Tecnología',
        'viajes' => 'Viajes'
    ];
    return $nombres[$clave] ?? $clave;
}
@endphp
</body>
</html>