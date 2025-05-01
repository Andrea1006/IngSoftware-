<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'tecnico') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Técnico</title>
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

        /* Responsive */
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
            <a href="servicio.php"><i class="fas fa-wrench"></i>Servicios</a>
            <a href="informes.php"><i class="fas fa-file-alt"></i>Informes</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Salir</a>
        </nav>

        <!-- Main content -->
        <div class="main-content">
            <h2>¡Hola, <?php echo $_SESSION['username']; ?>!</h2>
            <p>Bienvenido al panel técnico. Desde aquí puedes gestionar tus servicios, crear informes y estar al tanto de tus tareas.</p>
        </div>
    </div>

</body>
</html>
