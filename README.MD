# EvaluaCompraInador

Aplicación web en PHP para registrar cotizaciones de supermercados, generar listas de compras y administrar catálogos maestros del sistema.

## Funcionalidades

- Generación de listas de cotizaciones por fecha y supermercado.
- Registro y administración de compras.
- Creación de listas de compras en base a evaluaciones registradas.
- Mantenimiento de maestros para categorías, marcas, productos, supermercados y unidades.
- Utilidades comunes para formato de fechas y moneda.

## Estructura del proyecto

- `index.php`: página principal con acceso a los módulos del sistema.
- `generarListasCompras/`: gestión de listas de cotizaciones generadas.
- `cotizador/`: módulo de cotización y consulta de listas.
- `compras/`: módulo para crear y administrar listas de compras.
- `maestros/`: administración de catálogos maestros.
- `inc/`: funciones comunes y conexión a base de datos.
- `css/`, `JS/`, `jquery/`, `img/`: recursos estáticos del proyecto.

## Tecnologías

- PHP
- MySQL / MariaDB
- Bootstrap 5
- jQuery
- HTML, CSS y JavaScript

## Requisitos

- Servidor web compatible con PHP, recomendado XAMPP en Windows.
- Base de datos MySQL o MariaDB.
- Navegador moderno.

## Ejecución local

1. Copia el proyecto dentro de la carpeta `htdocs` de XAMPP.
2. Configura los datos de conexión en `inc/connection.php`.
3. Importa la base de datos si corresponde.
4. Abre el proyecto desde `http://localhost/evaluacomprainador/`.

## Notas

- El proyecto está pensado para uso interno y administración de compras.
- Los estilos y scripts se encuentran dentro de las carpetas `css/` y `JS/`.
