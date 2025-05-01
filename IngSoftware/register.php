<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Sin hash
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
        header("Location: register.php?success=1");
        exit();
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #E3F2FD, #BBDEFB);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: block;
        }
        h2 {
            text-align: center;
            color: #1E88E5;
            margin-bottom: 25px;
            font-weight: 600;
        }
        label {
            font-weight: 500;
            color: #444;
            margin: 10px 0 5px;
            display: block;
        }
        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f9f9f9;
        }
        input:focus, select:focus {
            border-color: #1E88E5;
            outline: none;
        }
        input[type="submit"] {
            background-color: #1E88E5;
            color: white;
            border: none;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #1565C0;
        }
        .error {
            color: #e53935;
            text-align: center;
            margin-bottom: 15px;
            font-weight: 500;
        }
        .back-btn {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #1E88E5;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }
        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Logo" class="logo">
        <h2>Registro de Usuario</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
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
            <select name="rol" required>
                <option value="administrador">Administrador</option>
                <option value="tecnico">Técnico</option>
            </select>
            
            <input type="submit" value="Registrarse">
        </form>
        <a href="login.php" class="back-btn">¿Ya tienes cuenta? Inicia sesión</a>
    </div>

    <?php
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo "<script>alert('Usuario registrado exitosamente'); window.location.href='login.php';</script>";
    }
    ?>
</body>
</html>
