<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Sin hash, como pediste
    $rol = $_POST['rol'];

    $sql = "INSERT INTO usuarios (nombre_completo, email, telefono, username, password, rol) 
            VALUES (:nombre_completo, :email, :telefono, :username, :password, :rol)";
    $stmt = $conn->prepare($sql);
    
    try {
        $stmt->execute([
            ':nombre_completo' => $nombre_completo,
            ':email' => $email,
            ':telefono' => $telefono,
            ':username' => $username,
            ':password' => $password,
            ':rol' => $rol
        ]);
        // Redirigir con un parámetro para mostrar el alert
        header("Location: register.php?success=1");
        exit();
    } catch(PDOException $e) {
        echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error { color: red; text-align: center; }
        .back-btn {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #4CAF50;
        }
        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>
        <form method="POST">
            <label>Nombre Completo:</label>
            <input type="text" name="nombre_completo" required>
            
            <label>Correo:</label>
            <input type="email" name="email" required>
            
            <label>Teléfono:</label>
            <input type="tel" name="telefono" required>
            
            <label>Usuario:</label>
            <input type="text" name="username" required>
            
            <label>Contraseña:</label>
            <input type="password" name="password" required>
            
            <label>Rol:</label>
            <select name="rol">
                <option value="administrador">Administrador</option>
                <option value="tecnico">Técnico</option>
            </select>
            
            <input type="submit" value="Registrarse">
        </form>
        <a href="login.php" class="back-btn">Volver al Inicio</a>
    </div>

    <?php
    // Mostrar alerta si el registro fue exitoso
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo "<script>alert('Usuario registrado exitosamente'); window.location.href='login.php';</script>";
    }
    ?>
</body>
</html>