@extends('layouts.app')
@section('title', 'Registrar Gasto')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Registrar Nuevo Gasto</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('gasto.guardar') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Tipo de gasto</label>
                        <select name="tipo" class="form-select" required id="tipoGasto">
                            <option value="">Seleccionar tipo...</option>
                            <option value="fijo">Fijo</option>
                            <option value="dinamico">Dinámico</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select" required id="categoriaGasto">
                            <option value="">Primero selecciona el tipo de gasto</option>
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
                        <button type="submit" class="btn btn-danger">Registrar Gasto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Categorías predefinidas
const categorias = {
    fijo: {
        'arriendo': 'Arriendo',
        'servicios': 'Servicios (luz, agua, internet)',
        'transporte_fijo': 'Transporte fijo',
        'seguros': 'Seguros',
        'educacion': 'Educación',
        'creditos': 'Créditos/Prestamos'
    },
    dinamico: {
        'mercado': 'Mercado/Alimentos',
        'restaurantes': 'Restaurantes',
        'ropa': 'Ropa/Calzado',
        'entretenimiento': 'Entretenimiento',
        'imprevistos': 'Imprevistos',
        'salud': 'Salud',
        'regalos': 'Regalos/Celebraciones',
        'hogar': 'Artículos para el hogar',
        'tecnologia': 'Tecnología',
        'viajes': 'Viajes'
    }
};

// Actualizar categorías cuando cambie el tipo
document.getElementById('tipoGasto').addEventListener('change', function() {
    const tipo = this.value;
    const selectCategoria = document.getElementById('categoriaGasto');
    
    // Limpiar opciones
    selectCategoria.innerHTML = '<option value="">Selecciona una categoría</option>';
    
    if (tipo && categorias[tipo]) {
        Object.entries(categorias[tipo]).forEach(([clave, valor]) => {
            const option = document.createElement('option');
            option.value = clave;
            option.textContent = valor;
            selectCategoria.appendChild(option);
        });
    }
});
</script>
@endpush
@endsection