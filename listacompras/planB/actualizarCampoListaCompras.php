<?php

require_once __DIR__ . '/../inc/connection.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($conn) || !($conn instanceof mysqli)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'No se pudo abrir la conexión a la base de datos.']);
    exit;
}

$action = $_POST['action'] ?? '';
$campo = $_POST['campo'] ?? '';
$valor = $_POST['valor'] ?? '';

$camposPermitidos = ['UM', 'marca', 'capacidad', 'cantidad', 'precio'];

function recalcularPrecioXUmPorId(mysqli $conn, int $idRegistro): void
{
    $stmt = $conn->prepare("UPDATE listacomprasprecios SET precio_x_um = CASE WHEN capacidad IS NULL OR capacidad = 0 THEN 0 ELSE precio / capacidad END, fecha_modificacion = NOW() WHERE id = ?");
    if (!$stmt) {
        throw new Exception('No se pudo recalcular precio_x_um.');
    }

    $stmt->bind_param('i', $idRegistro);
    $stmt->execute();
    $stmt->close();
}

function recalcularPrecioXUmPorProducto(mysqli $conn, string $mesCompra, int $idProducto): void
{
    $stmt = $conn->prepare("UPDATE listacomprasprecios SET precio_x_um = CASE WHEN capacidad IS NULL OR capacidad = 0 THEN 0 ELSE precio / capacidad END, fecha_modificacion = NOW() WHERE mes_compra = ? AND id_producto = ?");
    if (!$stmt) {
        throw new Exception('No se pudo recalcular precio_x_um.');
    }

    $stmt->bind_param('si', $mesCompra, $idProducto);
    $stmt->execute();
    $stmt->close();
}

if (!in_array($campo, $camposPermitidos, true)) {
    echo json_encode(['success' => false, 'message' => 'Campo no permitido.']);
    exit;
}

try {
    if ($action === 'actualizar_por_id') {
        $id = (int) ($_POST['id'] ?? 0);
        $idProducto = (int) ($_POST['id_producto'] ?? 0);
        $idSupermercado = (int) ($_POST['id_supermercado'] ?? 0);
        $mesCompra = trim((string) ($_POST['mes_compra'] ?? ''));

        if ($id <= 0 && ($idProducto <= 0 || $mesCompra === '' || ($campo === 'precio' && $idSupermercado <= 0))) {
            throw new Exception('ID de registro inválido.');
        }

        $valorDb = ($valor === '' ? 0 : $valor);
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE listacomprasprecios SET {$campo} = ?, fecha_modificacion = NOW() WHERE id = ?");
            if (!$stmt) {
                throw new Exception('No se pudo preparar la actualización.');
            }

            if (in_array($campo, ['UM', 'marca'], true)) {
                $valorDb = (int) $valorDb;
                $stmt->bind_param('ii', $valorDb, $id);
            } else {
                $valorDb = ($valorDb === '' ? 0 : $valorDb);
                $stmt->bind_param('di', $valorDb, $id);
            }

            $stmt->execute();
            $stmt->close();

            if ($campo === 'precio') {
                recalcularPrecioXUmPorId($conn, $id);
            }
        } else {
            if ($campo === 'precio') {
                $sql = "UPDATE listacomprasprecios SET {$campo} = ?, fecha_modificacion = NOW() WHERE mes_compra = ? AND id_producto = ? AND id_supermercado = ?";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception('No se pudo preparar la actualización.');
                }

                $valorDb = ($valorDb === '' ? 0 : $valorDb);
                $stmt->bind_param('dsii', $valorDb, $mesCompra, $idProducto, $idSupermercado);
                $stmt->execute();
                $stmt->close();

                recalcularPrecioXUmPorProducto($conn, $mesCompra, $idProducto);
            } else {
                $sql = "UPDATE listacomprasprecios SET {$campo} = ?, fecha_modificacion = NOW() WHERE mes_compra = ? AND id_producto = ?";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception('No se pudo preparar la actualización.');
                }

                if (in_array($campo, ['UM', 'marca'], true)) {
                    $valorDb = (int) $valorDb;
                    $stmt->bind_param('isi', $valorDb, $mesCompra, $idProducto);
                } else {
                    $valorDb = ($valorDb === '' ? 0 : $valorDb);
                    $stmt->bind_param('dsi', $valorDb, $mesCompra, $idProducto);
                }

                $stmt->execute();
                $stmt->close();

                if ($campo === 'capacidad') {
                    recalcularPrecioXUmPorProducto($conn, $mesCompra, $idProducto);
                }
            }
        }
    } elseif ($action === 'actualizar_por_producto') {
        $idProducto = (int) ($_POST['id_producto'] ?? 0);
        $mesCompra = trim((string) ($_POST['mes_compra'] ?? ''));

        if ($idProducto <= 0 || $mesCompra === '') {
            throw new Exception('Faltan datos del producto.');
        }

        $valorDb = ($valor === '' ? 0 : $valor);
        $sql = "UPDATE listacomprasprecios SET {$campo} = ?, fecha_modificacion = NOW() WHERE mes_compra = ? AND id_producto = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('No se pudo preparar la actualización.');
        }

        if (in_array($campo, ['UM', 'marca'], true)) {
            $valorDb = (int) $valorDb;
            $stmt->bind_param('isi', $valorDb, $mesCompra, $idProducto);
        } else {
            $valorDb = ($valorDb === '' ? 0 : $valorDb);
            $stmt->bind_param('dsi', $valorDb, $mesCompra, $idProducto);
        }

        $stmt->execute();
        $stmt->close();

        if ($campo === 'capacidad' || $campo === 'precio') {
            recalcularPrecioXUmPorProducto($conn, $mesCompra, $idProducto);
        }
    } else {
        throw new Exception('Acción no válida.');
    }

    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
