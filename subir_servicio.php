<?php
session_start();
// Verifica que el usuario sea administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

// Verifica que el user_id esté definido
if (!isset($_SESSION['user_id'])) {
    die("Error: No se ha iniciado sesión correctamente. Falta el ID del usuario.");
}

include 'db_connect.php'; // Archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $solicitud_no = $_POST['solicitud_no'] ?? '';
    $tipo_servicio = $_POST['tipo_servicio'] ?? '';
    $razon_social = $_POST['razon_social'] ?? '';
    $establecimiento = $_POST['establecimiento'] ?? '';
    $nit = $_POST['nit'] ?? '';
    $material = $_POST['material'] ?? '';
    $tipo_tanque = $_POST['tipo_tanque'] ?? '';
    $capacidad = $_POST['capacidad'] ?? 0;
    $producto = $_POST['producto'] ?? '';
    $ano_fabricacion = $_POST['ano_fabricacion'] ?? 0;
    $sucursal = $_POST['sucursal'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $created_by = $_SESSION['user_id']; 

    // Validar que los campos requeridos no estén vacíos
    if (empty($solicitud_no) || empty($tipo_servicio) || empty($razon_social) || empty($establecimiento) || empty($nit) || empty($material) || empty($tipo_tanque) || empty($capacidad) || empty($producto) || empty($ano_fabricacion) || empty($sucursal) || empty($usuario)) {
        die("Error: Todos los campos son obligatorios.");
    }

    try {
        $sql = "INSERT INTO servicios (
            solicitud_no,
            tipo_servicio,
            razon_social,
            establecimiento,
            nit,
            material,
            tipo_tanque,
            capacidad,
            producto,
            ano_fabricacion,
            sucursal,
            usuario,
            created_by
        ) VALUES (
            :solicitud_no,
            :tipo_servicio,
            :razon_social,
            :establecimiento,
            :nit,
            :material,
            :tipo_tanque,
            :capacidad,
            :producto,
            :ano_fabricacion,
            :sucursal,
            :usuario,
            :created_by
        )";

        $stmt = $conn->prepare($sql);

        // Vincular los parámetros uno por uno
        $stmt->bindParam(':solicitud_no', $solicitud_no, PDO::PARAM_STR);
        $stmt->bindParam(':tipo_servicio', $tipo_servicio, PDO::PARAM_STR);
        $stmt->bindParam(':razon_social', $razon_social, PDO::PARAM_STR);
        $stmt->bindParam(':establecimiento', $establecimiento, PDO::PARAM_STR);
        $stmt->bindParam(':nit', $nit, PDO::PARAM_STR);
        $stmt->bindParam(':material', $material, PDO::PARAM_STR);
        $stmt->bindParam(':tipo_tanque', $tipo_tanque, PDO::PARAM_STR);
        $stmt->bindParam(':capacidad', $capacidad, PDO::PARAM_INT);
        $stmt->bindParam(':producto', $producto, PDO::PARAM_STR);
        $stmt->bindParam(':ano_fabricacion', $ano_fabricacion, PDO::PARAM_INT);
        $stmt->bindParam(':sucursal', $sucursal, PDO::PARAM_STR);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':created_by', $created_by, PDO::PARAM_INT);

        // Depuración
        var_dump([
            ':solicitud_no' => $solicitud_no,
            ':tipo_servicio' => $tipo_servicio,
            ':razon_social' => $razon_social,
            ':establecimiento' => $establecimiento,
            ':nit' => $nit,
            ':material' => $material,
            ':tipo_tanque' => $tipo_tanque,
            ':capacidad' => $capacidad,
            ':producto' => $producto,
            ':ano_fabricacion' => $ano_fabricacion,
            ':sucursal' => $sucursal,
            ':usuario' => $usuario,
            ':created_by' => $created_by
        ]);

        $stmt->execute();

        echo "<script>alert('Servicio registrado correctamente'); window.location.href='admin_dashboard.php';</script>";
    } catch (PDOException $e) {
        die("Error al registrar el servicio: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Servicio </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            margin: 0;
        }
        .sidebar {
            width: 200px;
            background-color: #e0e0e0;
            padding: 20px;
            height: 100vh;
            box-sizing: border-box;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin-bottom: 15px;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
        }
        .sidebar ul li a i {
            margin-right: 10px;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: white;
        }
        .form-section label {
            display: block;
            margin: 10px 0 5px;
        }
        .form-section input, .form-section select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-section button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-section button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="subir_servicio.php"><i class="fas fa-plus"></i> Servicios</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
        </ul>
    </div>

    <!-- Área principal -->
    <div class="main-content">
        <h2>Subir Nuevo Servicio</h2>
        <div class="form-section">
            <form method="POST">
                <label>Solicitud No.:</label>
                <input type="text" name="solicitud_no" required>

                <label>Tipo de Servicio:</label>
                <input type="text" name="tipo_servicio" required>

                <label>Razón Social:</label>
                <input type="text" name="razon_social" required>

                <label>Establecimiento:</label>
                <input type="text" name="establecimiento" required>

                <label>NIT:</label>
                <input type="text" name="nit" required>

                <label>Material:</label>
                <input type="text" name="material" required>

                <label>Tipo de Tanque:</label>
                <select name="tipo_tanque" required>
                    <option value="Subterráneo">Subterráneo</option>
                    <option value="Superficial">Superficial</option>
                </select>

                <label>Capacidad en galones:</label>
                <input type="number" name="capacidad" required>

                <label>Producto:</label>
                <input type="text" name="producto" required>

                <label>Año de Fabricación:</label>
                <input type="number" name="ano_fabricacion" required>

                <label>Sucursal:</label>
                <input type="text" name="sucursal" required>

                <label>Usuario Asignado:</label>
                <input type="text" name="usuario" required>

                <button type="submit">Subir Servicio</button>
            </form>
        </div>
    </div>
</body>
</html>