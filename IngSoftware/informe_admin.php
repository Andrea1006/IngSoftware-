<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

$sql = "SELECT i.id, i.servicio_id, i.fecha_inicio, i.estado, s.razon_social, s.sucursal, u.username
        FROM informes i
        LEFT JOIN servicios s ON i.servicio_id = s.id
        LEFT JOIN usuarios u ON i.created_by = u.id
        ORDER BY i.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$informes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            background-color: #6f42c1;
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

        .sidebar a:hover, .sidebar a.active {
            background-color: #0056b3;
        }

        .main-content {
            flex: 1;
            padding: 50px;
            overflow-y: auto;
            background-color: white;
            animation: fadeIn 1s ease;
        }

        .main-content h2 {
            font-size: 2.5em;
            color: #007BFF;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        .estado-en_espera {
            background: #d3d3d3;
            color: black;
            padding: 5px 10px;
            border-radius: 8px;
            font-weight: bold;
        }

        .estado-aprobado {
            background: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 8px;
            font-weight: bold;
        }

        .estado-no_aprobado {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 8px;
            font-weight: bold;
        }

        .action-icons a {
            margin-right: 10px;
            text-decoration: none;
            color: #6f42c1;
        }

        .action-icons a:hover {
            color: #4a2c91;
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
        <div class="user-info">ADMINISTRADOR</div>
    </div>

    <div class="container">
        <nav class="sidebar">
            <a href="admin_dashboard.php"><i class="fas fa-home"></i>Inicio</a>
            <a href="subir_servicio.php"><i class="fas fa-wrench"></i>Servicios</a>
            <a href="informes_admin.php" class="active"><i class="fas fa-file-alt"></i>Informes</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Salir</a>
        </nav>

        <div class="main-content">
            <div class="breadcrumb">
                <a href="admin_dashboard.php">Inicio</a> > Informes
            </div>
            <h2>Lista de Informes</h2>
            <?php if (count($informes) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Razón Social</th>
                            <th>Sucursal</th>
                            <th>Usuario</th>
                            <th>Fecha de Inicio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($informes as $informe): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($informe['razon_social'] ?? 'No disponible'); ?></td>
                                <td><?php echo htmlspecialchars($informe['sucursal'] ?? 'No disponible'); ?></td>
                                <td><?php echo htmlspecialchars($informe['username'] ?? 'No disponible'); ?></td>
                                <td><?php echo htmlspecialchars($informe['fecha_inicio']); ?></td>
                                <td>
                                    <span class="estado-<?php echo $informe['estado']; ?>">
                                        <?php
                                        if ($informe['estado'] == 'en_espera') echo 'En espera';
                                        elseif ($informe['estado'] == 'aprobado') echo 'Aprobado';
                                        else echo 'No aprobado';
                                        ?>
                                    </span>
                                </td>
                                <td class="action-icons">
                                    <a href="ver_informe.php?id=<?php echo $informe['id']; ?>" title="Ver Detalles"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay informes disponibles.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>