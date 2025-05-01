<?php
session_start();
// Verifica que el usuario sea administrador y esté autenticado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    die("Error: No se ha iniciado sesión correctamente. Falta el ID del usuario.");
}

include 'db_connect.php'; // Archivo de conexión a la base de datos

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
    <title>Panel Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts y Font Awesome -->
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

        h2, h3 {
            color: #007BFF;
            margin-bottom: 15px;
        }

        p {
            color: #555;
            font-size: 1.1em;
            margin-bottom: 20px;
        }

        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .services-table th, .services-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .services-table th {
            background-color: #007BFF;
            color: white;
        }

        .services-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .services-table tr:hover {
            background-color: #e1f0ff;
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
            <a href="subir_servicio.php"><i class="fas fa-wrench"></i>Servicio</a>
            <a href="informe_admin.php"><i class="fas fa-file-alt"></i>Informes</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Salir</a>
        </nav>

        <div class="main-content">
            <h2>Bienvenido, <?php echo $_SESSION['username']; ?> (Administrador)</h2>
            <p>Este es el panel para administradores. Aquí puedes gestionar los servicios e informes.</p>

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
    </div>
</body>
</html>
