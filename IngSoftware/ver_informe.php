<?php
session_start();
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['tecnico', 'administrador'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

$informe_id = $_GET['id'];
$sql = "SELECT i.*, s.razon_social, s.sucursal, s.solicitud_no, s.tipo_servicio, s.establecimiento
        FROM informes i
        LEFT JOIN servicios s ON i.servicio_id = s.id
        WHERE i.id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $informe_id]);
$informe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$informe) {
    die("Error: Informe no encontrado.");
}

// Verificar permisos
if ($_SESSION['rol'] == 'tecnico' && $informe['created_by'] != $_SESSION['user_id']) {
    die("Error: No tienes permiso para ver este informe.");
}

// Procesar aprobación (solo para administradores)
if ($_SESSION['rol'] == 'administrador' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $estado = $_POST['estado'];
    $correcciones = $_POST['correcciones'] ?? null;

    $sql = "UPDATE informes SET estado = :estado, correcciones = :correcciones WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':estado' => $estado,
        ':correcciones' => $correcciones,
        ':id' => $informe_id
    ]);

    echo "<script>alert('Estado actualizado correctamente'); window.location.href='informe_admin.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Informe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; display: flex; }
        .sidebar { width: 200px; background: #e0e0e0; padding: 20px; height: 100vh; }
        .sidebar a { display: flex; align-items: center; color: #333; text-decoration: none; margin-bottom: 15px; }
        .sidebar a i { margin-right: 10px; }
        .sidebar a.active { background: #28a745; color: white; padding: 5px 10px; border-radius: 5px; }
        .main-content { flex-grow: 1; padding: 20px; background: white; }
        .informe-details { max-width: 600px; }
        .informe-details p { margin: 10px 0; }
        .informe-details p strong { display: inline-block; width: 150px; }
        .informe-details img { max-width: 300px; margin: 10px 0; }
        .estado-en_espera { background: #d3d3d3; color: black; padding: 5px; border-radius: 5px; }
        .estado-aprobado { background: #28a745; color: white; padding: 5px; border-radius: 5px; }
        .estado-no_aprobado { background: #dc3545; color: white; padding: 5px; border-radius: 5px; }
        .back-link { display: inline-block; margin-top: 20px; text-decoration: none; color: #007bff; }
        .back-link:hover { text-decoration: underline; }
        .admin-form { margin-top: 20px; }
        .admin-form label { display: block; margin: 10px 0 5px; }
        .admin-form select, .admin-form textarea { width: 100%; padding: 8px; margin-bottom: 10px; }
        .admin-form button { background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .admin-form button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="<?php echo $_SESSION['rol'] == 'administrador' ? 'admin_dashboard.php' : 'tecnico_dashboard.php'; ?>"><i class="fas fa-home"></i> Inicio</a>
        <a href="<?php echo $_SESSION['rol'] == 'administrador' ? 'subir_servicio.php' : 'servicio.php'; ?>"><i class="fas fa-wrench"></i> Servicios</a>
        <a href="<?php echo $_SESSION['rol'] == 'administrador' ? 'informes_admin.php' : 'informes.php'; ?>" class="active"><i class="fas fa-file-alt"></i> Informes</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a>
    </div>
    <div class="main-content">
        <div class="informe-details">
            <h2>Detalles del Informe</h2>
            <h3>Datos del Servicio</h3>
            <p><strong>Solicitud No.:</strong> <?php echo htmlspecialchars($informe['solicitud_no'] ?? 'No disponible'); ?></p>
            <p><strong>Tipo de Servicio:</strong> <?php echo htmlspecialchars($informe['tipo_servicio'] ?? 'No disponible'); ?></p>
            <p><strong>Razón Social:</strong> <?php echo htmlspecialchars($informe['razon_social'] ?? 'No disponible'); ?></p>
            <p><strong>Establecimiento:</strong> <?php echo htmlspecialchars($informe['establecimiento'] ?? 'No disponible'); ?></p>

            <h3>Datos del Informe</h3>
            <p><strong>Fecha de Inicio:</strong> <?php echo htmlspecialchars($informe['fecha_inicio']); ?></p>
            <p><strong>Observaciones:</strong> <?php echo htmlspecialchars($informe['observaciones']); ?></p>
            <p><strong>Resultado de la Prueba:</strong> <?php echo htmlspecialchars($informe['resultado']); ?></p>
            <p><strong>Medida Inicial:</strong> <?php echo htmlspecialchars($informe['medida_inicial']); ?> cm</p>
            <p><strong>Hora de Inicio:</strong> <?php echo htmlspecialchars($informe['hora_inicio']); ?></p>
            <p><strong>Medida Final:</strong> <?php echo htmlspecialchars($informe['medida_final']); ?> cm</p>
            <p><strong>Hora de Fin:</strong> <?php echo htmlspecialchars($informe['hora_fin']); ?></p>
            <p><strong>Estado:</strong>
                <span class="estado-<?php echo $informe['estado']; ?>">
                    <?php
                    if ($informe['estado'] == 'en_espera') echo 'En espera';
                    elseif ($informe['estado'] == 'aprobado') echo 'Aprobado';
                    else echo 'No aprobado';
                    ?>
                </span>
            </p>
            <?php if ($informe['estado'] == 'no_aprobado' && $informe['correcciones']): ?>
                <p><strong>Correcciones:</strong> <?php echo htmlspecialchars($informe['correcciones']); ?></p>
            <?php endif; ?>

            <h3>Evidencias Fotográficas</h3>
            <p><strong>Evidencia 1:</strong><br><img src="<?php echo htmlspecialchars($informe['imagen1']); ?>" alt="Evidencia 1"></p>
            <?php if ($informe['imagen2']): ?>
                <p><strong>Evidencia 2:</strong><br><img src="<?php echo htmlspecialchars($informe['imagen2']); ?>" alt="Evidencia 2"></p>
            <?php endif; ?>

            <?php if ($_SESSION['rol'] == 'administrador'): ?>
                <div class="admin-form">
                    <h3>Aprobar Informe</h3>
                    <form method="POST">
                        <label>Estado:</label>
                        <select name="estado" required>
                            <option value="en_espera" <?php if ($informe['estado'] == 'en_espera') echo 'selected'; ?>>En espera</option>
                            <option value="aprobado" <?php if ($informe['estado'] == 'aprobado') echo 'selected'; ?>>Aprobado</option>
                            <option value="no_aprobado" <?php if ($informe['estado'] == 'no_aprobado') echo 'selected'; ?>>No aprobado</option>
                        </select>
                        <label>Correcciones (si no aprobado):</label>
                        <textarea name="correcciones"><?php echo htmlspecialchars($informe['correcciones'] ?? ''); ?></textarea>
                        <button type="submit">Guardar</button>
                    </form>
                </div>
            <?php endif; ?>

            <a href="<?php echo $_SESSION['rol'] == 'administrador' ? 'informe_admin.php' : 'informes.php'; ?>" class="back-link">Volver a Informes</a>
        </div>
    </div>
</body>
</html>