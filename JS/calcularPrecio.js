/**
 * Calcula el precio por unidad de medida
 * @param {string|HTMLElement} capacidadSelector - ID del elemento o el elemento mismo de capacidad
 * @param {string|HTMLElement} precioSelector - ID del elemento o el elemento mismo de precio
 * @param {string|HTMLElement} precioPorUmSelector - ID del elemento o el elemento mismo de precio por UM
 */
function calcularPrecioPorUM(capacidadSelector, precioSelector, precioPorUmSelector) {
    // Obtener elementos
    const capacidadInput = typeof capacidadSelector === 'string' 
        ? document.getElementById(capacidadSelector) 
        : capacidadSelector;
    const precioInput = typeof precioSelector === 'string' 
        ? document.getElementById(precioSelector) 
        : precioSelector;
    const precioPorUmInput = typeof precioPorUmSelector === 'string' 
        ? document.getElementById(precioPorUmSelector) 
        : precioPorUmSelector;

    if (!capacidadInput || !precioInput || !precioPorUmInput) {
        console.warn('No se encontraron todos los elementos necesarios para calcular precio por UM');
        return;
    }

    const capacidad = parseFloat(capacidadInput.value);
    const precio = parseFloat(precioInput.value);

    if (!Number.isFinite(capacidad) || !Number.isFinite(precio) || capacidad <= 0) {
        precioPorUmInput.value = '';
        return;
    }

    const resultado = precio / capacidad;
    precioPorUmInput.value = resultado.toFixed(0);
}

/**
 * Inicializa los event listeners para el formulario principal
 */
function inicializarCalculoPrecioFormularioPrincipal() {
    const capacidadInput = document.getElementById('capacidad');
    const precioInput = document.getElementById('precio');

    if (capacidadInput && precioInput) {
        ['input', 'change'].forEach(evento => {
            capacidadInput.addEventListener(evento, () => {
                calcularPrecioPorUM('capacidad', 'precio', 'precioporum');
            });
            precioInput.addEventListener(evento, () => {
                calcularPrecioPorUM('capacidad', 'precio', 'precioporum');
            });
        });

        calcularPrecioPorUM('capacidad', 'precio', 'precioporum');
    }
}

/**
 * Inicializa los event listeners para filas dinámicas en tabla
 * @param {string} prefijo - Prefijo usado en los IDs de los inputs (ej: 'fila_1_')
 */
function inicializarCalculoPrecioFila(prefijo) {
    const capacidadId = prefijo + 'capacidad';
    const precioId = prefijo + 'precio';
    const precioPorUmId = prefijo + 'precioporum';

    const capacidadInput = document.getElementById(capacidadId);
    const precioInput = document.getElementById(precioId);

    if (capacidadInput && precioInput) {
        ['input', 'change'].forEach(evento => {
            capacidadInput.addEventListener(evento, () => {
                calcularPrecioPorUM(capacidadId, precioId, precioPorUmId);
            });
            precioInput.addEventListener(evento, () => {
                calcularPrecioPorUM(capacidadId, precioId, precioPorUmId);
            });
        });

        // Calcular inicial
        calcularPrecioPorUM(capacidadId, precioId, precioPorUmId);
    }
}
