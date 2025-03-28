<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'tecnico') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

// Consultar los informes del técnico
$user_id = $_SESSION['user_id'];
$sql = "SELECT i.id, i.servicio_id, i.fecha_inicio, i.estado, s.razon_social, s.sucursal
        FROM informes i
        LEFT JOIN servicios s ON i.servicio_id = s.id
        WHERE i.created_by = :user_id
        ORDER BY i.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$informes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; display: flex; }
        .sidebar { width: 200px; background: #e0e0e0; padding: 20px; height: 100vh; }
        .sidebar a { display: flex; align-items: center; color: #333; text-decoration: none; margin-bottom: 15px; }
        .sidebar a i { margin-right: 10px; }
        .sidebar a.active { background: #28a745; color: white; padding: 5px 10px; border-radius: 5px; }
        .main-content { flex-grow: 1; padding: 20px; background: white; }
        .informes-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .informes-table th, .informes-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .informes-table th { background: #007bff; color: white; }
        .informes-table tr:nth-child(even) { background: #f2f2f2; }
        .informes-table tr:hover { background: #ddd; }
        .estado-en_espera { background: #d3d3d3; color: black; padding: 5px; border-radius: 5px; }
        .estado-aprobado { background: #28a745; color: white; padding: 5px; border-radius: 5px; }
        .estado-no_aprobado { background: #dc3545; color: white; padding: 5px; border-radius: 5px; }
        .action-icons a { margin-right: 10px; text-decoration: none; color: #007bff; }
        .action-icons a:hover { color: #0056b3; }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="tecnico_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="servicio.php"><i class="fas fa-wrench"></i> Servicios</a>
        <a href="informes.php" class="active"><i class="fas fa-file-alt"></i> Informes</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a>
    </div>
    <div class="main-content">
        <h2>Lista de Informes</h2>
        <?php if (count($informes) > 0): ?>
            <table class="informes-table">
                <thead>
                    <tr>
                        <th>Razón Social</th>
                        <th>Sucursal</th>
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
            <p>No has creado ningún informe todavía.</p>
        <?php endif; ?>
    </div>
</body>
</html>