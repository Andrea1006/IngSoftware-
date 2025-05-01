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
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #A8D8E8, #E2F0F6); /* Azul suave y claro */
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* Header */
        .header {
            background-color: #007BFF; /* Azul brillante */
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
            background-color: #17A2B8; /* Azul suave */
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            color: white;
        }

        /* Layout */
        .container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            background: #007BFF; /* Azul brillante */
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
            background-color: #0056b3; /* Azul más oscuro al pasar el mouse */
        }

        /* Main content */
        .main-content {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            animation: fadeIn 1s ease;
            background-color: white;
        }

        .main-content h2 {
            font-size: 2.5em;
            color: #007BFF; /* Azul brillante */
            margin-bottom: 20px;
        }

        .main-content p {
            font-size: 1.2em;
            color: #555;
            max-width: 600px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Table */
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

        /* Sidebar responsive */
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

    <!-- Header -->
    <div class="header">
        <div class="app-title">Panel Técnico</div>
        <div class="user-info"><?php echo strtoupper($_SESSION['username']); ?> - Técnico</div>
    </div>

    <!-- Layout -->
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <a href="tecnico_dashboard.php"><i class="fas fa-home"></i>Inicio</a>
            <a href="servicio.php" class="active"><i class="fas fa-wrench"></i>Servicios</a>
            <a href="informes.php"><i class="fas fa-file-alt"></i>Informes</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Salir</a>
        </nav>

        <!-- Main content -->
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
    </div>

</body>
</html>
