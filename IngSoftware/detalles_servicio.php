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
    <title>Detalles del Servicio</title>
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
            height: 100vh;
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

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #0056b3;
        }

        .main-content {
            flex: 1;
            padding: 50px;
            overflow-y: auto;
            background-color: white;
            animation: fadeIn 1s ease;
        }

        .breadcrumb {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .breadcrumb a {
            text-decoration: none;
            color: #007BFF;
        }

        h2 {
            color: #007BFF;
            margin-bottom: 20px;
        }

        .details-section p {
            margin: 10px 0;
            font-size: 1.1em;
            color: #333;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            body {
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
    <!-- Sidebar -->
    <nav class="sidebar">
        <a href="tecnico_dashboard.php"><i class="fas fa-home"></i>Inicio</a>
        <a href="servicios.php" class="active"><i class="fas fa-wrench"></i>Servicios</a>
        <a href="informes.php"><i class="fas fa-file-alt"></i>Informes</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Salir</a>
    </nav>

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