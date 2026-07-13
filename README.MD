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

## Base de datos

La aplicación usa la base de datos `evaluacomprainador`.

### Tablas principales

- `categorias`: `id`, `codigo`, `descripcion`, `estado`, `icono`.
- `marcas`: `id`, `codigo`, `descripcion`, `estado`.
- `productos`: `id`, `codigo`, `descripcion`, `categoria`, `estado`.
- `supermercados`: `id`, `codigo`, `descripcion`, `logo`, `estado`.
- `unidades`: `id`, `codigo`, `descripcion`, `estado`.
- `cotizador_mensual`: `mes_compra`, `supermercado`, `producto`, `marca`, `um`, `capacidad`, `precio`, `precio_por_um`.
- `lista_compras`: `id`, `supermercado`, `producto`, `um`, `marca`, `capacidad`, `precio`, `menor_precio_por_um`, `cantidad_compra`, `total_por_producto`, `estado`.

### Relaciones usadas por la aplicación

- `productos.categoria` referencia `categorias.id`.
- `cotizador_mensual.supermercado` referencia `supermercados.id`.
- `cotizador_mensual.producto` referencia `productos.id`.
- `cotizador_mensual.marca` referencia `marcas.id`.
- `cotizador_mensual.um` referencia `unidades.id`.
- `lista_compras.supermercado` referencia `supermercados.id`.
- `lista_compras.producto` referencia `productos.id`.
- `lista_compras.marca` referencia `marcas.id`.
- `lista_compras.um` referencia `unidades.id`.

### Procedimientos almacenados

- `sp_generar_precios(fecha, supermercados)`: genera o regenera registros en `cotizador_mensual` para una fecha y uno o varios supermercados.
- `sp_precio_menor_por_um(fecha_inicial, fecha_final, producto, cantidad)`: calcula el mejor precio por unidad para un producto en un rango de fechas.

### Manejo de estados

- Las tablas maestras usan el campo `estado` para mantener registros activos o inactivos.
- En `lista_compras`, `estado = 0` representa pendiente y `estado = 1` representa compra registrada.

## Ejecución local

1. Copia el proyecto dentro de la carpeta `htdocs` de XAMPP.
2. Configura los datos de conexión en `inc/connection.php`.
3. Importa la base de datos si corresponde.
4. Abre el proyecto desde `http://localhost/evaluacomprainador/`.

## Notas

- El proyecto está pensado para uso interno y administración de compras.
- Los estilos y scripts se encuentran dentro de las carpetas `css/` y `JS/`.
