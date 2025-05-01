<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'tecnico') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar user_id
    if (!isset($_SESSION['user_id'])) {
        die("Error: user_id no está definido en la sesión.");
    }
    if (!is_numeric($_SESSION['user_id'])) {
        die("Error: user_id no es un valor numérico válido.");
    }

    // Obtener datos del formulario
    $servicio_id = $_POST['servicio_id'] ?? null;
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $observaciones = $_POST['observaciones'] ?? null;
    $resultado = $_POST['resultado'] ?? null;
    $medida_inicial = $_POST['medida_inicial'] ?? null;
    $hora_inicio = $_POST['hora_inicio'] ?? null;
    $medida_final = $_POST['medida_final'] ?? null;
    $hora_fin = $_POST['hora_fin'] ?? null;

    // Manejo de las imágenes
    $imagen1 = null;
    $imagen2 = null;

    if (isset($_FILES['imagen1']) && $_FILES['imagen1']['error'] == UPLOAD_ERR_OK) {
        $imagen1 = 'uploads/' . basename($_FILES['imagen1']['name']);
        move_uploaded_file($_FILES['imagen1']['tmp_name'], $imagen1);
    }

    if (isset($_FILES['imagen2']) && $_FILES['imagen2']['error'] == UPLOAD_ERR_OK) {
        $imagen2 = 'uploads/' . basename($_FILES['imagen2']['name']);
        move_uploaded_file($_FILES['imagen2']['tmp_name'], $imagen2);
    }

    // Depuración
    var_dump($servicio_id); // Verificar servicio_id
    var_dump($_SESSION['user_id']); // Verificar user_id

    // Insertar el informe en la base de datos
    $sql = "INSERT INTO informes (servicio_id, fecha_inicio, observaciones, resultado, medida_inicial, hora_inicio, medida_final, hora_fin, imagen1, imagen2, created_by)
            VALUES (:servicio_id, :fecha_inicio, :observaciones, :resultado, :medida_inicial, :hora_inicio, :medida_final, :hora_fin, :imagen1, :imagen2, :created_by)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error al preparar la consulta: " . print_r($conn->errorInfo(), true));
    }

    $params = [
        ':servicio_id' => $servicio_id,
        ':fecha_inicio' => $fecha_inicio,
        ':observaciones' => $observaciones,
        ':resultado' => $resultado,
        ':medida_inicial' => $medida_inicial,
        ':hora_inicio' => $hora_inicio,
        ':medida_final' => $medida_final,
        ':hora_fin' => $hora_fin,
        ':imagen1' => $imagen1,
        ':imagen2' => $imagen2,
        ':created_by' => $_SESSION['user_id']
    ];

    try {
        $stmt->execute($params);
        header("Location: informes.php");
        exit();
    } catch (PDOException $e) {
        die("Error al insertar el informe: " . $e->getMessage());
    }
}

$servicio_id = $_GET['servicio_id'];
$sql = "SELECT s.id, s.razon_social, s.sucursal, s.solicitud_no, s.tipo_servicio, s.establecimiento
        FROM servicios s
        WHERE s.id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $servicio_id]);
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$servicio) {
    die("Error: Servicio no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Informe - SOCA 2 NEXT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; display: flex; }
        .sidebar { width: 200px; background: #e0e0e0; padding: 20px; height: 100vh; }
        .sidebar a { display: flex; align-items: center; color: #333; text-decoration: none; margin-bottom: 15px; }
        .sidebar a i { margin-right: 10px; }
        .sidebar a.active { background: #28a745; color: white; padding: 5px 10px; border-radius: 5px; }
        .main-content { flex-grow: 1; padding: 20px; background: white; }
        .form-container { max-width: 600px; }
        .form-container h2, .form-container h3 { margin-bottom: 20px; }
        .form-container label { display: block; margin: 10px 0 5px; }
        .form-container input, .form-container select, .form-container textarea { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .form-container button { background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .form-container button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="inicio.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="lista_servicios.php" class="active"><i class="fas fa-wrench"></i> Servicios</a>
        <a href="informes.php"><i class="fas fa-file-alt"></i> Informes</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a>
    </div>
    <div class="main-content">
        <div class="form-container">
            <h2>Iniciar Informe</h2>
            <h3>Datos del Servicio</h3>
            <p><strong>Solicitud No.:</strong> <?php echo htmlspecialchars($servicio['solicitud_no']); ?></p>
            <p><strong>Tipo de Servicio:</strong> <?php echo htmlspecialchars($servicio['tipo_servicio']); ?></p>
            <p><strong>Razón Social:</strong> <?php echo htmlspecialchars($servicio['razon_social']); ?></p>
            <p><strong>Establecimiento:</strong> <?php echo htmlspecialchars($servicio['establecimiento']); ?></p>

            <h3>Datos del Informe</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="servicio_id" value="<?php echo $servicio['id']; ?>">
                <label>Fecha de Inicio:</label>
                <input type="date" name="fecha_inicio" required>
                <label>Observaciones:</label>
                <textarea name="observaciones" required></textarea>
                <label>Resultado de la Prueba:</label>
                <select name="resultado" required>
                    <option value="Efectivo">Efectivo</option>
                    <option value="No Efectivo">No Efectivo</option>
                </select>
                <label>Medida Inicial (cm):</label>
                <input type="number" name="medida_inicial" step="0.01" required>
                <label>Hora de Inicio:</label>
                <input type="time" name="hora_inicio" required>
                <label>Medida Final (cm):</label>
                <input type="number" name="medida_final" step="0.01" required>
                <label>Hora de Fin:</label>
                <input type="time" name="hora_fin" required>
                <label>Evidencia Fotográfica 1:</label>
                <input type="file" name="imagen1" accept="image/*" required>
                <label>Evidencia Fotográfica 2 (Opcional):</label>
                <input type="file" name="imagen2" accept="image/*">
                <button type="submit">Guardar Informe</button>
            </form>
        </div>
    </div>
</body>
</html>