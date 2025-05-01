<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}
if (!isset($_SESSION['user_id'])) {
    die("Error: No se ha iniciado sesión correctamente. Falta el ID del usuario.");
}

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    if (empty($solicitud_no) || empty($tipo_servicio) || empty($razon_social) || empty($establecimiento) || empty($nit) || empty($material) || empty($tipo_tanque) || empty($capacidad) || empty($producto) || empty($ano_fabricacion) || empty($sucursal) || empty($usuario)) {
        die("Error: Todos los campos son obligatorios.");
    }

    try {
        $sql = "INSERT INTO servicios (
            solicitud_no, tipo_servicio, razon_social, establecimiento, nit, material,
            tipo_tanque, capacidad, producto, ano_fabricacion, sucursal, usuario, created_by
        ) VALUES (
            :solicitud_no, :tipo_servicio, :razon_social, :establecimiento, :nit, :material,
            :tipo_tanque, :capacidad, :producto, :ano_fabricacion, :sucursal, :usuario, :created_by
        )";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
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
    <title>Subir Servicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #A8D8E8, #E2F0F6);
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .header {
            background-color: #007BFF;
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .app-title {
            font-size: 2em;
            font-weight: bold;
        }

        .user-info {
            background-color: #17A2B8;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            color: white;
        }

        .container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .sidebar {
            background: #007BFF;
            width: 200px;
            display: flex;
            flex-direction: column;
            padding-top: 30px;
            gap: 25px;
            align-items: center;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            color: white;
            font-size: 1.2em;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        .main-content {
            flex: 1;
            padding: 50px;
            overflow-y: auto;
            animation: fadeIn 1s ease;
        }

        h2 {
            color: #007BFF;
            margin-bottom: 25px;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: 600;
            margin-top: 15px;
            display: block;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            margin-top: 20px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                flex-direction: row;
                width: 100%;
                height: 70px;
                padding: 10px;
                justify-content: space-around;
            }

            .main-content {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="app-title">Panel Administrador</div>
        <div class="user-info"><?php echo strtoupper($_SESSION['username']); ?> - Administrador</div>
    </div>

    <div class="container">
        <nav class="sidebar">
            <a href="admin_dashboard.php"><i class="fas fa-home"></i>Inicio</a>
            <a href="subir_servicio.php"><i class="fas fa-plus"></i>Servicio</a>
            <a href="informe_admin.php"><i class="fas fa-file-alt"></i>Informes</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Salir</a>
            
        </nav>

        <div class="main-content">
            <h2>Subir Nuevo Servicio</h2>
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
                <input type="text" name="tipo_tanque" required>

                <label>Capacidad:</label>
                <input type="number" name="capacidad" required>

                <label>Producto:</label>
                <input type="text" name="producto" required>

                <label>Año de Fabricación:</label>
                <input type="number" name="ano_fabricacion" required>

                <label>Sucursal:</label>
                <input type="text" name="sucursal" required>

                <label>Usuario:</label>
                <input type="text" name="usuario" required>

                <button type="submit">Registrar Servicio</button>
            </form>
        </div>
    </div>
</body>
</html>
