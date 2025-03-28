<?php
session_start();
// Verifica que el usuario sea técnico y esté autenticado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'tecnico') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php'; // Archivo de conexión a la base de datos

$servicio_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "SELECT * FROM servicios WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $servicio_id]);
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$servicio) {
    echo "Servicio no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Servicio </title>
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
        .sidebar ul li a.active {
            background-color: #4CAF50;;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: white;
        }
        .breadcrumb {
            margin-bottom: 20px;
            font-size: 14px;
            color: #555;
        }
        .breadcrumb a {
            text-decoration: none;
            color: #4CAF50;;
        }
        h2 {
            margin-bottom: 20px;
        }
        .details-section {
            margin-bottom: 20px;
        }
        .details-section p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li><a href="tecnico_dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="servicios.php" class="active"><i class="fas fa-wrench"></i> Servicios</a></li>
            <li><a href="informes.php"><i class="fas fa-file-alt"></i> Informes</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
        </ul>
    </div>

    <!-- Área principal -->
    <div class="main-content">
        <div class="breadcrumb">
            <a href="tecnico_dashboard.php">Inicio</a> > <a href="servicio.php">Servicios</a> > Detalles del Servicio
        </div>
        <h2>Evaluación</h2>
        <div class="details-section">
            <p><strong>Solicitud No.:</strong> <?php echo htmlspecialchars($servicio['solicitud_no']); ?></p>
            <p><strong>Tipo de Servicio:</strong> <?php echo htmlspecialchars($servicio['tipo_servicio']); ?></p>
            <p><strong>Razón Social:</strong> <?php echo htmlspecialchars($servicio['razon_social']); ?></p>
            <p><strong>Establecimiento:</strong> <?php echo htmlspecialchars($servicio['establecimiento']); ?></p>
            <p><strong>NIT:</strong> <?php echo htmlspecialchars($servicio['nit']); ?></p>
            <p><strong>Material:</strong> <?php echo htmlspecialchars($servicio['material']); ?></p>
            <p><strong>Tipo de Tanque:</strong> <?php echo htmlspecialchars($servicio['tipo_tanque']); ?></p>
            <p><strong>Capacidad en galones:</strong> <?php echo htmlspecialchars($servicio['capacidad']); ?></p>
            <p><strong>Producto:</strong> <?php echo htmlspecialchars($servicio['producto']); ?></p>
            <p><strong>Año de Fabricación:</strong> <?php echo htmlspecialchars($servicio['ano_fabricacion']); ?></p>
            <p><strong>Sucursal:</strong> <?php echo htmlspecialchars($servicio['sucursal']); ?></p>
            <p><strong>Usuario Asignado:</strong> <?php echo htmlspecialchars($servicio['usuario']); ?></p>
            <p><strong>Fecha de Inicio:</strong> <?php echo htmlspecialchars($servicio['fecha_inicio'] ?: 'No definida'); ?></p>
        </div>
    </div>
</body>
</html>