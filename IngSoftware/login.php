<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['password'] === $password) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nombre_completo'] = $user['nombre_completo']; 
        $_SESSION['rol'] = $user['rol'];
        
        if ($user['rol'] == 'administrador') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: tecnico_dashboard.php");
        }
        exit();
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #E3F2FD, #BBDEFB); /* Azul armónico */
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
            max-width: 380px;
            text-align: center;
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }
        h2 {
            color: #1E88E5; /* Azul para los títulos */
            font-weight: 600;
            margin-bottom: 30px;
        }
        label {
            font-weight: 500;
            color: #444;
            margin: 12px 0 6px;
            display: block;
            text-align: left;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f9f9f9;
            transition: border-color 0.2s ease;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #1E88E5;
            outline: none;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #1E88E5; /* Azul principal */
            color: white;
            border: none;
            font-weight: 600;
            font-size: 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #1565C0;
        }
        .error {
            color: #e53935;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .register-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #1E88E5; /* Azul para el enlace */
            font-size: 14px;
            text-decoration: none;
            font-weight: 500;
        }
        .register-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo (puedes cambiar src a tu propio logo) -->
        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Logo" class="logo">

        <h2>Iniciar Sesión</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" value="Iniciar Sesión">
        </form>
        <a href="register.php" class="register-btn">¿No tienes cuenta? Regístrate</a>
    </div>
</body>
</html>
