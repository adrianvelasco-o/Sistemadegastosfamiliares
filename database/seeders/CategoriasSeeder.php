<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriasSeeder extends Seeder
{
    public function run()
    {
        // Categorías para gastos FIJOS
        $categoriasFijas = [
            ['nombre' => 'Arriendo', 'descripcion' => 'Alquiler de vivienda'],
            ['nombre' => 'Servicios', 'descripcion' => 'Luz, agua, gas, internet'],
            ['nombre' => 'Transporte', 'descripcion' => 'Pasajes, combustible fijo'],
            ['nombre' => 'Seguros', 'descripcion' => 'Salud, vida, hogar'],
            ['nombre' => 'Educación', 'descripcion' => 'Colegiatura, cursos fijos'],
            ['nombre' => 'Créditos', 'descripcion' => 'Pagos mensuales de préstamos'],
        ];

        foreach ($categoriasFijas as $cat) {
            Categoria::create([
                'nombre' => $cat['nombre'],
                'tipo_gasto' => 'fijo',
                'descripcion' => $cat['descripcion']
            ]);
        }

        // Categorías para gastos DINÁMICOS
        $categoriasDinamicas = [
            ['nombre' => 'Mercado', 'descripcion' => 'Alimentos y productos básicos'],
            ['nombre' => 'Restaurantes', 'descripcion' => 'Comidas fuera de casa'],
            ['nombre' => 'Ropa', 'descripcion' => 'Vestimenta y calzado'],
            ['nombre' => 'Entretenimiento', 'descripcion' => 'Cine, eventos, suscripciones'],
            ['nombre' => 'Imprevistos', 'descripcion' => 'Gastos no planificados'],
            ['nombre' => 'Salud', 'descripcion' => 'Medicinas, consultas ocasionales'],
            ['nombre' => 'Regalos', 'descripcion' => 'Presentes y celebraciones'],
            ['nombre' => 'Hogar', 'descripcion' => 'Decoración, utensilios'],
            ['nombre' => 'Tecnología', 'descripcion' => 'Electrónicos, accesorios'],
            ['nombre' => 'Viajes', 'descripcion' => 'Turismo y desplazamientos'],
        ];

        foreach ($categoriasDinamicas as $cat) {
            Categoria::create([
                'nombre' => $cat['nombre'],
                'tipo_gasto' => 'dinamico',
                'descripcion' => $cat['descripcion']
            ]);
        }
    }
}