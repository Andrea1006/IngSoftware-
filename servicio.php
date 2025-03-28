<?php
session_start();
// Verifica que el usuario sea técnico y esté autenticado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'tecnico') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php'; // Archivo de conexión a la base de datos

// Obtener los servicios de la base de datos
$sql = "SELECT id, razon_social, sucursal, usuario FROM servicios";
$stmt = $conn->prepare($sql);
$stmt->execute();
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .actions i {
            margin-right: 10px;
            color: #6f42c1;
            cursor: pointer;
        }
        .export-btn {
            text-align: right;
            margin-top: 10px;
        }
        .export-btn a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }
        .export-btn i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li><a href="tecnico_dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="servicio.php" class="active"><i class="fas fa-wrench"></i> Servicios</a></li>
            <li><a href="informes.php"><i class="fas fa-file-alt"></i> Informes</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
        </ul>
    </div>

    <!-- Área principal -->
    <div class="main-content">
        <div class="breadcrumb">
            <a href="tecnico_dashboard.php">Inicio</a> > Servicios
        </div>
        <h2>Lista de Servicios</h2>
        <table>
            <thead>
                <tr>
                    <th>Reporte</th>
                    <th>Sucursal</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($servicios as $servicio): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($servicio['razon_social']); ?></td>
                        <td><?php echo htmlspecialchars($servicio['sucursal']); ?></td>
                        <td><?php echo htmlspecialchars($servicio['usuario']); ?></td>
                        <td class="actions">
                            <a href="detalles_servicio.php?id=<?php echo $servicio['id']; ?>"><i class="fas fa-eye" title="Ver Detalles"></i></a>
                            <a href="iniciar_informe.php?id=<?php echo $servicio['id']; ?>"><i class="fas fa-edit" title="Iniciar Informe"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="export-btn">
            <a href="#"><i class="fas fa-file-excel"></i> Exportar a Excel</a>
        </div>
    </div>
</body>
</html>