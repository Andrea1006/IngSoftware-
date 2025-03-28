<?php
session_start();
// Verifica que el usuario sea administrador y esté autenticado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

// Verifica que el user_id esté definido
if (!isset($_SESSION['user_id'])) {
    die("Error: No se ha iniciado sesión correctamente. Falta el ID del usuario.");
}

include 'db_connect.php'; // Archivo de conexión a la base de datos

// Consultar los servicios creados por el administrador
$user_id = $_SESSION['user_id'];
$sql = "SELECT solicitud_no, tipo_servicio, razon_social, establecimiento, fecha_inicio, created_at 
        FROM servicios 
        WHERE created_by = :user_id 
        ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            margin: 0;
        }
        .header {
            background-color:   #4CAF50;
            color: black;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px  #4CAF50;
        }
        .header .app-name {
            font-size: 1.5em;
            font-weight: bold;
            text-transform: uppercase;
            background: linear-gradient(to right, #333, #555);
            -webkit-background-clip: text;
            color: transparent;
        }
        .header .user-info {
            font-size: 1em;
            color: black;
            text-transform: uppercase;
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
            background-color:#4CAF50;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .main-content {
            flex: 1;
            padding: 0px;
        
        }

        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .services-table th, .services-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .services-table th {
            background-color: #4CAF50;
            color: white;
        }
        .services-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .services-table tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li><a href="tecnico_dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="subir_servicio.php"><i class="fas fa-wrench"></i> Servicio</a></li>
            <li><a href="informe_admin.php"><i class="fas fa-file-alt"></i> Informes</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
        </ul>
    </div>

    <!-- Área principal -->
    <div class="main-content">
        <div class="header">
            <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?> (Administrador)</h2>
            <a href="logout.php" class="logout">Cerrar Sesión</a>
        </div>
        <p>Este es el panel para administradores. Aquí puedes gestionar los servicios e informes.</p>

        <!-- Tabla de servicios -->
        <h3>Servicios Creados</h3>
        <?php if (count($servicios) > 0): ?>
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Solicitud No.</th>
                        <th>Tipo de Servicio</th>
                        <th>Razón Social</th>
                        <th>Establecimiento</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Creación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicios as $servicio): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($servicio['solicitud_no']); ?></td>
                            <td><?php echo htmlspecialchars($servicio['tipo_servicio']); ?></td>
                            <td><?php echo htmlspecialchars($servicio['razon_social']); ?></td>
                            <td><?php echo htmlspecialchars($servicio['establecimiento']); ?></td>
                            <td><?php echo htmlspecialchars($servicio['fecha_inicio'] ?? 'No definida'); ?></td>
                            <td><?php echo htmlspecialchars($servicio['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No has creado ningún servicio todavía.</p>
        <?php endif; ?>
    </div>
</body>
</html>